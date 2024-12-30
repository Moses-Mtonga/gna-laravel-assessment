<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use ZipArchive;
use Exception;

class ModuleController extends Controller
{
    // module listing function
    public function index()
    {
        $modules = json_decode(File::get(base_path('modules_statuses.json')), true);
        $moduleStatuses = [];
        // check if the module is installed, if it is, we can activate it in the view
        foreach ($modules as $module => $status) {
            $moduleStatuses[$module] = [
                'active' => $status,
                'installed' => $this->isModuleInstalled($module),
            ];
        }


        return view('system_settings.module_index', compact('moduleStatuses'));
    }

    public function install($moduleName)
    {
        // dd($moduleName);
        // Lets activate the module
        $modules = json_decode(File::get(base_path('modules_statuses.json')), true);
        $modules[$moduleName] = true;
        File::put(base_path('modules_statuses.json'), json_encode($modules, JSON_PRETTY_PRINT));

        // Ensure Rolling back module migrations
        Artisan::call('module:migrate-rollback', [
            'module' => $moduleName,
        ]);

        // Run module migrations
        Artisan::call('module:migrate', [
            'module' => $moduleName,
        ]);


        // Ensure the bootstrap/cache directory exists and is writable
        $bootstrapCachePath = base_path('bootstrap/cache');

        // Create the bootstrap cache directory if it does not exist
        if (!File::exists($bootstrapCachePath)) {
            File::makeDirectory($bootstrapCachePath, 0755, true);
        }

        // Clear bootstrap cache content except the cache folder
        $files = File::files($bootstrapCachePath);
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore') {
                File::delete($file);
            }
        }

        // lets clear out residual cache and optimize the application
        // Artisan::call('optimize');

        return redirect()->route('modules.index')->with('success', 'Module installed successfully.');
    }

    public function delete($moduleName)
    {
        // Load the modules statuses
        $modules = json_decode(File::get(base_path('modules_statuses.json')), true);

        // Remove the module entry
        unset($modules[$moduleName]);

        // Save the updated statuses
        File::put(base_path('modules_statuses.json'), json_encode($modules, JSON_PRETTY_PRINT));

        // delete data
        // Rollback module migrations
        Artisan::call('module:migrate-rollback', [
            'module' => $moduleName,
        ]);

        // If need be, we remove module files
        File::deleteDirectory(base_path('Modules/' . $moduleName));

        // Ensure the bootstrap/cache directory exists and is writable
        $bootstrapCachePath = base_path('bootstrap/cache');

        // Create the bootstrap cache directory if it does not exist
        if (!File::exists($bootstrapCachePath)) {
            File::makeDirectory($bootstrapCachePath, 0755, true);
        }

        // Clear bootstrap cache content except the cache folder
        $files = File::files($bootstrapCachePath);
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore') {
                File::delete($file);
            }
        }

        return redirect()->route('modules.index')->with('success', 'Module uninstalled successfully.');
    }

    // updating modules
    public function update(Request $request)
    {
        $modules = $request->input('modules', []);
        $allModules = json_decode(File::get(base_path('modules_statuses.json')), true);

        foreach ($allModules as $module => $status) {
            $allModules[$module] = in_array($module, $modules);
        }
        // lets update module status file as per the request
        File::put(base_path('modules_statuses.json'), json_encode($allModules, JSON_PRETTY_PRINT));

        // Ensure the bootstrap/cache directory exists and is writable
        $bootstrapCachePath = base_path('bootstrap/cache');

        // Create the bootstrap cache directory if it does not exist
        if (!File::exists($bootstrapCachePath)) {
            File::makeDirectory($bootstrapCachePath, 0755, true);
        }

        // Clear bootstrap cache content except the cache folder
        $files = File::files($bootstrapCachePath);
        foreach ($files as $file) {
            if ($file->getFilename() !== '.gitignore') {
                File::delete($file);
            }
        }

        return redirect()->route('modules.index')->with('success', 'Modules updated successfully.');
    }

    public function uploadNewModule(Request $request)
    {
        $request->validate([
            'moduleZip' => 'required|mimes:zip'
        ]);

        $file = $request->file('moduleZip');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $modulePath = base_path('Modules/' . $fileName);

        // Check if the module name already exists
        $modules = json_decode(File::get(base_path('modules_statuses.json')), true);
        if (array_key_exists($fileName, $modules)) {
            return redirect()->route('modules.index')->with('error', 'Module with this name already exists.');
        }

        try {
            // Create the module directory
            if (!File::exists($modulePath)) {
                File::makeDirectory($modulePath, 0755, true);
            }

            // Move the uploaded file to the module directory
            $file->move($modulePath, $file->getClientOriginalName());

            // lets now extract the zip file
            $zip = new ZipArchive;
            if ($zip->open($modulePath . '/' . $file->getClientOriginalName()) === TRUE) {
                $zip->extractTo($modulePath);
                $zip->close();

                // Delete the uploaded zip file
                File::delete($modulePath . '/' . $file->getClientOriginalName());

                // Register the new module in the modules_statuses.json file
                $modules[$fileName] = false;
                File::put(base_path('modules_statuses.json'), json_encode($modules, JSON_PRETTY_PRINT));

                return redirect()->route('modules.index')->with('success', 'Module uploaded successfully. Please install it to use it');
            } else {
                throw new Exception('Failed to extract the module.');
            }
        } catch (Exception $e) {
            // Handle any errors that occur during the process
            return redirect()->route('modules.index')->with('error', $e->getMessage());
        }
    }

    public function isModuleInstalled($moduleName)
    {
        // lets define one of the tables that should exist for the module
        $tables = [
            'LoanManagement' => ['loans'],
            'FarmSupport' => ['farm_supports'],
        ];

        if (!isset($tables[$moduleName])) {
            return false;
        }

        foreach ($tables[$moduleName] as $table) {
            if (!Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }
}
