<?php

namespace App\Http\Controllers;

use App\Models\Offense;
use Illuminate\Http\Request;

class OffenseController extends Controller
{
    public function store(Request $request)
    {
        $request->merge(['code' => strtoupper(trim((string) $request->code))]);

        $data = $request->validateWithBag('offense', [
            'code' => ['required', 'string', 'max:10', 'regex:/^[A-Z0-9-]+$/', 'unique:offenses,code'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:minor,major'],
            'sanction_path' => ['nullable', 'array', 'max:5'],
            'sanction_path.*' => ['nullable', 'in:A,B,C,D,E,F'],
        ]);

        $path = null;
        if ($data['category'] === 'major') {
            $path = array_values(array_filter($data['sanction_path'] ?? []));
            if (count($path) === 0) {
                return back()->withInput()->withErrors(
                    ['sanction_path' => 'A major offense needs at least one sanction step.'],
                    'offense'
                );
            }
        }

        Offense::create([
            'code' => $data['code'],
            'category' => $data['category'],
            'title' => $data['title'],
            'sanction_path' => $path,
        ]);

        return redirect()->route('admin.violation.monitoring')
            ->with('success', "Violation {$data['code']} added. It is now in the selection list.");
    }
}