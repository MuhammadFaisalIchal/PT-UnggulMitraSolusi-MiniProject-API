<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

class BarangController extends Controller
{
    public function create(Request $request)
    {
        $newCode = "";
        $newInt  = 0;
        $brgInt = DB::select('SELECT SUBSTRING(kode, 5, 1) + 1 AS newcode FROM barang ORDER BY SUBSTRING(kode, 5, 1) DESC LIMIT 1;');

        if (count($brgInt) > 0) {
            foreach ($brgInt as $value) {
                $newInt = $value->newcode;
            }
            $newCode = "BRG_" . $newInt;
        } else {
            $newCode = "BRG_1";
        }

        try {
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'required|string|max:255',
                'kategori' => 'required|string|max:255',
                'harga' => 'required|regex:/^\d+(\.\d{1,2})?$/'
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error' => response()->json($validator->errors(), 400)
                ], 'Validation Failed', 500);
            } else {
                Barang::create([
                    'kode' => $newCode,
                    'nama_barang' => $request->nama_barang,
                    'kategori' => $request->kategori,
                    'harga' => $request->harga
                ]);

                return ResponseFormatter::success(
                    NULL,
                    'Barang berhasil diinput'
                );
            }
        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Gagal input data barang',
                    'error' => $error
                ],
                'Insertion Failed',
                500
            );
        }

    }

    public function read(Request $request)
    {
        $kode = $request->input('kode');

        if ($kode) {
            $barang = Barang::where('kode', $kode)->get();

            if (!$barang->isEmpty()) {
                return ResponseFormatter::success($barang, 'Data barang berhasil diambil');
            } else {
                return ResponseFormatter::error(null, 'Data barang tidak ada', 404);
            }
        } else {
            $barang = DB::table('barang')->get();

            if (!$barang->isEmpty()) {
                return ResponseFormatter::success($barang, 'Data barang berhasil diambil');
            } else {
                return ResponseFormatter::error(null, 'Data barang tidak ada', 404);
            }
        }
    }

    public function update(Request $request)
    {
        $barang = Barang::where('kode', $request->input('kode'))->get();

        if (!$barang->isEmpty()) {
            try {
                $validator = Validator::make($request->all(), [
                    'kode' => 'required|string|max:255',
                    'nama_barang' => 'required|string|max:255',
                    'kategori' => 'required|max:255',
                    'harga' => 'required|regex:/^\d+(\.\d{1,2})?$/'
                ]);

                if ($validator->fails()) {
                    return ResponseFormatter::error([
                        'message' => 'Something went wrong',
                        'error' => response()->json($validator->errors(), 400)
                    ], 'Validation Failed', 500);
                }

                Barang::where('kode', $request->input('kode'))->update([
                    'nama_barang' => $request->input('nama_barang'),
                    'kategori' => $request->input('kategori'),
                    'harga' => $request->input('harga')
                ]);

                return ResponseFormatter::success(null, 'Barang berhasil diperbarui');
            } catch (Exception $error) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error' => $error
                ], 'Barang gagal diperbarui', 500);
            }
        } else {
            return ResponseFormatter::error(null, 'Data barang tidak ada', 404);
        }
    }


    public function delete(Request $request)
    {
        $kode = $request->input('kode');
        $barang = Barang::where('kode', $kode)->get();

        if (!$barang->isEmpty()) {
            Barang::where('kode', $kode)->delete();
            return ResponseFormatter::success(null, 'Barang berhasil dihapus');
        } else {
            return ResponseFormatter::error(null, 'Barang gagal dihapus', 404);
        }
    }
}
