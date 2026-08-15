<?php

namespace App\Services;

use Illuminate\Http\Request;

class PrescriptionBuilder
{
    /**
     * Susun data resep dari input yang sudah tervalidasi.
     * Cylinder & axis hanya berlaku untuk resep 'full' (Lensa) metode manual.
     */
    public function build(Request $request, array $validated, string $prescriptionType): array
    {
        $method = $validated['prescription_method'];

        $prescription = [
            'type' => $prescriptionType,
            'method' => $method,
            'sph_right' => null,
            'sph_left' => null,
            'cyl_right' => null,
            'cyl_left' => null,
            'axis_right' => null,
            'axis_left' => null,
            'file' => null,
            'note' => $validated['prescription_note'] ?? null,
        ];

        if ($method === 'manual') {
            return $this->fillManualMeasurements($prescription, $validated, $prescriptionType);
        }

        if ($request->hasFile('prescription_file')) {
            $prescription['file'] = $request->file('prescription_file')->store('prescriptions', 'public');
        }

        return $prescription;
    }

    private function fillManualMeasurements(array $prescription, array $validated, string $prescriptionType): array
    {
        $prescription['sph_right'] = $validated['sph_right'] ?? null;
        $prescription['sph_left'] = $validated['sph_left'] ?? null;

        if ($prescriptionType === 'full') {
            $prescription['cyl_right'] = $validated['cyl_right'] ?? null;
            $prescription['cyl_left'] = $validated['cyl_left'] ?? null;
            $prescription['axis_right'] = $validated['axis_right'] ?? null;
            $prescription['axis_left'] = $validated['axis_left'] ?? null;
        }

        return $prescription;
    }
}