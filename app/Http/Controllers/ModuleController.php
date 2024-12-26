<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

class ModuleController extends Controller
{
    // module listing modules
    public function index()
    {
        $modules = json_decode(File::get(base_path('modules_statuses.json')), true);
        $moduleStatuses = [];

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
        // Lets activate the module
        $modules = json_decode(File::get(base_path('modules_statuses.json')), true);
        $modules[$moduleName] = true;
        File::put(base_path('modules_statuses.json'), json_encode($modules, JSON_PRETTY_PRINT));

        // Run module migrations
        Artisan::call('module:migrate', [
            'module' => $moduleName,
        ]);

        // lets clear out residual cache and optimize the application
        Artisan::call('optimize');

        return redirect()->route('modules.index')->with('success', 'Module installed successfully.');
    }

    public function delete($moduleName)
    {
        // Deactivate the module
        $modules = json_decode(File::get(base_path('modules_statuses.json')), true);
        $modules[$moduleName] = false;
        File::put(base_path('modules_statuses.json'), json_encode($modules, JSON_PRETTY_PRINT));

        // delete data
        // Rollback module migrations
        Artisan::call('module:migrate-rollback', [
            'module' => $moduleName,
        ]);

        // If need be, we remove module files
        // File::deleteDirectory(base_path('Modules/' . $moduleName));

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
        // lets update module status files as per the request
        File::put(base_path('modules_statuses.json'), json_encode($allModules, JSON_PRETTY_PRINT));

        return redirect()->route('modules.index')->with('success', 'Modules updated successfully.');
    }

    public function uploadNewModule(Request $request)
    {
        $request->validate([
            'moduleZip' => 'required|mimes:zip'
        ]);

        $file = $request->file('moduleZip');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $destinationPath = base_path('Modules');

        // Check if the module name already exists
        $modules = json_decode(File::get(base_path('modules_statuses.json')), true);
        // if (array_key_exists($fileName, $modules)) {
        //     return redirect()->route('modules.index')->with('error', 'Module with this name already exists. Please check if this Module does not already exist in the system!');
        // }

        // Move the uploaded file to the Modules directory
        $file->move($destinationPath, $file->getClientOriginalName());

        // lets now extract the zip file
        $zip = new ZipArchive;
        if ($zip->open($destinationPath . '/' . $file->getClientOriginalName()) === TRUE) {
            $zip->extractTo($destinationPath);
            $zip->close();

            // Delete the uploaded zip file
            File::delete($destinationPath . '/' . $file->getClientOriginalName());

            // Register the new module in the modules_statuses.json file
            $modules[$fileName] = false;
            File::put(base_path('modules_statuses.json'), json_encode($modules, JSON_PRETTY_PRINT));

            return redirect()->route('modules.index')->with('success', 'Module uploaded and installed successfully.');
        } else {
            return redirect()->route('modules.index')->with('error', 'Failed to extract the module.');
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
