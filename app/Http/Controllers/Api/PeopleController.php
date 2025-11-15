<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;

/**
 * Controlador API para Personas
 *
 * Gestiona las operaciones CRUD para las personas del sistema.
 * Incluye filtrado por tipo de persona (proveedor, cliente, etc.).
 */
class PeopleController extends Controller
{
    /**
     * Listar personas con filtrado opcional
     *
     * Devuelve una lista de personas. Permite filtrar por tipo
     * mediante parámetro de query 'type'.
     *
     * @param Request $request Parámetros de filtrado
     * @return \Illuminate\Http\JsonResponse Lista de personas filtradas
     */
    public function index(Request $request)
    {
        $query = Person::query();

        // Filtrar por tipo si se proporciona
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $people = $query->get();

        return response()->json([
            'status' => true,
            'people' => $people
        ]);
    }

    /**
     * Crear una nueva persona
     *
     * Crea una nueva persona en el sistema con los datos proporcionados.
     *
     * @param Request $request Datos de la nueva persona
     * @return \Illuminate\Http\JsonResponse Persona creada
     */
    public function store(Request $request)
    {
        $person = Person::create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Person created successfully!",
            'person' => $person
        ], 200);
    }

    /**
     * Actualizar una persona existente
     *
     * Actualiza los datos de una persona específica.
     * Utiliza route model binding para inyección automática.
     *
     * @param Request $request Datos actualizados
     * @param Person $person Instancia de la persona a actualizar
     * @return \Illuminate\Http\JsonResponse Persona actualizada
     */
    public function update(Request $request, Person $person)
    {
        $person->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Person updated successfully!",
            'person' => $person
        ], 200);
    }

    /**
     * Eliminar una persona
     *
     * Elimina una persona específica del sistema.
     * Utiliza route model binding para inyección automática.
     *
     * @param Person $person Instancia de la persona a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
     */
    public function destroy(Person $person)
    {
        $person->delete();
        return response()->json([
            'status' => true,
            'message' => 'Person deleted successfully!'
        ], 200);
    }
}
