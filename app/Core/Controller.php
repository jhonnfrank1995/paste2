<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Controlador base.
 * Proporciona acceso a objetos comunes como Request, Response y Session.
 */
abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected Session $session;
    protected Validator $validator;

    public function __construct()
    {
        // En una aplicación a gran escala, estos serían inyectados por un
        // Contenedor de Inyección de Dependencias (DIC).
        // Para mantenerlo ligero, los instanciamos directamente.
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->validator = new Validator();
    }

    /**
     * Carga y devuelve una instancia de un modelo.
     */
    protected function model(string $modelName): mixed
    {
        $class = "App\\Models\\" . $modelName;
        if (class_exists($class)) {
            return new $class();
        }
        throw new \Exception("Model {$modelName} not found.");
    }
}