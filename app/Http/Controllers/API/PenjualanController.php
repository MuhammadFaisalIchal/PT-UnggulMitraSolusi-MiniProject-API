<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnValue;

class PenjualanController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subtotal' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'items.*.kode' => 'exists:barang,kode'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => response()->json($validator->errors(), 400)
            ], 'Validation Failed', 500);
        } else {
            $newCode = "";
            $newInt  = 0;
            $notaInt = DB::select('SELECT SUBSTRING(id_nota, 6, 1) + 1 AS newcode FROM penjualan ORDER BY SUBSTRING(id_nota, 6, 1) DESC LIMIT 1;');

            if (count($notaInt) > 0) {
                foreach ($notaInt as $value) {
                    $newInt = $value->newcode;
                }
                $newCode = "NOTA_" . $newInt;
            } else {
                $newCode = "NOTA_1";
            }

            $penjualan = Penjualan::create([
                'id_nota' => $newCode,
                'tgl' => Carbon::now()->format('Y-m-d'),
                'kode_pelanggan' => Auth::user()->id,
                'subtotal' => $request->subtotal
            ]);

            foreach ($request->items as $items => $value) {
                PenjualanItem::create([
                    'id_nota' => $penjualan->id_nota,
                    'kode_barang' => $value["kode"],
                    'qty' => $value["qty"]
                ]);
            }

            return ResponseFormatter::success($penjualan->load('penjualan_item.barang'), 'Transaksi berhasil');
        }
    }

    public function read(Request $request)
    {
        $id = $request->input('id_nota');
        $limit = $request->input('limit', 6);

        if ($id) {
            $transaction = Penjualan::with('penjualan_item.barang')
                ->where('id_nota', $id)
                ->where('kode_pelanggan', Auth::user()->id)
                ->get();

            if (!$transaction->isEmpty()) {
                return ResponseFormatter::success($transaction, 'Data transaksi berhasil diambil');
            } else {
                return ResponseFormatter::error(null, 'Data transaksi tidak ada', 404);
            }
        }

        $transaction = Penjualan::with('penjualan_item.barang')->where('kode_pelanggan', Auth::user()->id);

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data transaksi berhasil diambil'
        );
    }

    public function update(Request $request)
    {
        return "update";
    }

    public function delete(Request $request)
    {
        $id_nota = $request->input('id_nota');
        $penjualan = Penjualan::where('id_nota', $id_nota)->get();

        if (!$penjualan->isEmpty()) {
            Penjualan::where('id_nota', $id_nota)->delete();
            return ResponseFormatter::success(null, 'Transaksi berhasil dihapus');
        } else {
            return ResponseFormatter::error(null, 'Transaksi gagal dihapus', 404);
        }
    }
}
