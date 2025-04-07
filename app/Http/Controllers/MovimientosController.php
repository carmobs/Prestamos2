<?php

namespace App\Http\Controllers;

Use Datetime;
use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\Abono;
use Carbon\Carbon;
use App\Models\Det_Emp_Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MovimientosController extends Controller
{
    /**
     * Presenta una lista de todos los préstamos registrados en el sistema
     */
    public function prestamosGet(): View
    {
        $prestamos = Prestamo::join("empleado", "prestamo.fk_id_empleado", "=", "empleado.id_empleado")->get();
        
        return view('Movimientos/prestamosGet', [
            'prestamos' => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Préstamos" => url("/Movimientos/prestamos")
            ]
        ]);
    }

    public function prestamosAgregarGet(): View
    {
        $haceunanno=(new Datetime("-1 year"))->format("Y-m-d");
        $empleados = Empleado::where("fecha_ingreso", "<", $haceunanno)->get()->all();
        $fecha_actual = SupportCarbon::now();
        $prestamosvigentes = prestamo::where("fecha_ini_desc","<=",$fecha_actual)->where("fecha_fin_desc",">=",$fecha_actual)->get()->all();
        $empleados=array_column($empleados,null,"id_empleado");
        $prestamosvigentes=array_column($prestamosvigentes,null,"fk_id_empleado");
        $empleados=array_diff_key($empleados,$prestamosvigentes);
        return view('Movimientos/prestamosAgregarGet', [
            "empleados" => $empleados,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Préstamos" => url("/Movimientos/prestamos"),
                "Agregar" => url("/Movimientos/prestamos/agregar")
            ]
        ]);
    }

    public function prestamosAgregarPost(Request $request)
    {
        $fk_id_empleado = $request->input("fk_id_empleado");
        $monto = $request->input("monto");
        
        $puesto = Puesto::join("emp_puesto", "puesto.id_puesto", "=", "emp_puesto.fk_id_puesto")
            ->where("emp_puesto.fk_id_empleado", "=", $fk_id_empleado)
            ->whereNull("emp_puesto.fecha_fin")
            ->first();
        
        $sueldoX6 = $puesto->sueldo * 6;
        
        if ($monto > $sueldoX6) {
            return view("/error", ["error" => "La solicitud excede el monto permitido"]);
        }

        $fecha_solicitud = $request->input("fecha_solicitud");
        $plazo = $request->input("plazo");
        $fecha_scr = $request->input("fecha_scr");
        $tasa_mental = $request->input("tasa_mensual");
        $pago_fijo_cap = $request->input("pago_fijo_cap");
        $fecha_ini_desc = $request->input("fecha_ini_desc");
        $fecha_fin_desc = $request->input("fecha_fin_desc");
        $saldo_actual = $request->input("saldo_actual");
        $estado = $request->input("estado");

        $prestamo = new Prestamo([
            "fk_id_empleado" => $fk_id_empleado,
            "fecha_solicitud" => $fecha_solicitud,
            "monto" => $monto,
            "plazo" => $plazo,
            "fecha_scr" => $fecha_scr,
            "tasa_mental" => $tasa_mental,
            "pago_fijo_cap" => $pago_fijo_cap,
            "fecha_ini_desc" => $fecha_ini_desc,
            "fecha_fin_desc" => $fecha_fin_desc,
            "saldo_actual" => $saldo_actual,
            "estado" => $estado,
        ]);

        $prestamo->save();

        return redirect("/movimientos/prestamos"); // Redirige al listado de préstamos
    }

    public function abonosGet($id_prestamo): View
    {
        $abonos = Abono::where("fk_id_prestamo", $id_prestamo)->get();

        // Obtener el préstamo con su relación de empleado
        $prestamo = Prestamo::with('empleado')->where("id_prestamo", $id_prestamo)->first();

        return view('Movimientos/abonosGet', [
            'abonos' => $abonos,
            'prestamo' => $prestamo,
            'breadcrumbs' => [
                "Inicio" => url("/"),
                "Prestamos" => url("/Movimientos/prestamos"),
                "Abonos" => url("/Movimientos/prestamos/abonos"),
            ]
        ]);
    }

    public function abonosAgregarGet($id_prestamo): View
    {
        $prestamo = Prestamo::join("empleado", "empleado.id_empleado", "=", "prestamo.fk_id_empleado")
            ->where("id_prestamo", $id_prestamo)
            ->select("prestamo.*", "empleado.nombre") // Asegurar que obtienes todos los campos
            ->first();
        
        // Obtener el último abono registrado
        $ultimo_abono = Abono::where("fk_id_prestamo", $id_prestamo)
        ->orderBy("id_abono", "desc") // Asegurar que tomamos el último abono
        ->first();

        $abonos = Abono::where("fk_id_prestamo", $id_prestamo)->get();
        
        $num_abono = count($abonos) + 1;
        $saldo_anterior = $ultimo_abono ? $ultimo_abono->saldo_pendiente : $prestamo->monto; 
        $monto_capital = $prestamo->pago_fijo_cap;
        $monto_otros = $saldo_anterior * $prestamo->tasa_mental / 100;
        $monto_colocado = $monto_capital + $monto_otros;
        $saldo_pendiente = $saldo_anterior - $monto_capital;

        if ($saldo_pendiente < 0) {
        $monto_capital += $saldo_pendiente;
        $saldo_pendiente = 0;
        }

        return view('Movimientos/abonosAgregarGet', [
            'prestamo' => $prestamo,
            'num_abono' => $num_abono,
            'monto_capital' => $monto_capital,
            'monto_otros' => $monto_otros,
            'monto_colocado' => $monto_colocado,
            'saldo_pendiente' => $saldo_pendiente,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/Movimientos/prestamos"),
                "Abonos" => url("/prestamos/{$prestamo->id_prestamo}/abonos"),
                "Agregar" => "",
            ]
        ]);
    }

    public function abonosAgregarPost(Request $request)
    {
        $fk_id_prestamo = $request->input("fk_id_prestamo");
        $num_abono = $request->input("num_abono");
        $fecha = $request->input("fecha");
        $monto_capital = $request->input("monto_capital");
        $monto_otros = $request->input("monto_interes");
        $monto_cobrado = $request->input("monto_cobrado");
        $saldo_pendiente = $request->input("saldo_pendiente");

        $abono = new Abono([
            "fk_id_prestamo" => $fk_id_prestamo,
            "num_abono" => $num_abono,
            "fecha" => $fecha,
            "monto_capital" => $monto_capital,
            "monto_otros" => $monto_otros, 
            "monto_cobrado" => $monto_cobrado,
            "saldo_pendiente" => $saldo_pendiente,
        ]);

        $abono->save();

        $prestamo = Prestamo::find($fk_id_prestamo);
        $prestamo->saldo_actual = $saldo_pendiente;

        if ($saldo_pendiente < 0.01) {
            $prestamo->estado = "CONCLUIDO";
        }

        $prestamo->save();

        return redirect("/prestamos/{$fk_id_prestamo}/abonos");
    }

    public function empleadosPrestamosGet(Request $request, $id_empleado): View
    {
        $empleado = Empleado::find($id_empleado);

        $prestamos = Prestamo::where("prestamo.fk_id_empleado", $id_empleado)->get();

        return view('Movimientos/empleadosPrestamosGet', [
            "empleado" => $empleado,
            'prestamos' => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url("/Movimientos/prestamos")
            ]
        ]);
    }
}
