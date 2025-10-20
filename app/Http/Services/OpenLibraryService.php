<?php

namespace App\Http\Services;

use App\Http\Repositories\AuthorRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OpenLibraryService
{
    /**
     * Constructor.
     *
     * @param AuthorRepository $author_repository Repositorio de autores.
     * @return void
     */
    public function __construct(private AuthorRepository $author_repository)
    {}

    /**
     * Arreglo de nombres de autores de libros conocidos.
     *
     * @var array
     * @author Fahibram Cárcamo
     */
    const WELL_KNOWN_AUTHORS = [
        'Gabriel García Márquez',
        'Franz Kafka',
        'Julio Verne',
        'Albert Camus',
        'Juan Rulfo',
        'Stieg Larsson',
        'Ernesto Sabato',
        'Edgar Allan Poe',
        'Agatha Christie',
        'Stephen King',
        'Julio Cortázar',
        'George R. R. Martin',
        'J. K. Rowling',
        'Alexandre Dumas',
        'Ernest Hemingway'
    ];

    /**
     * Realiza una petición HTTP a la API de Open Library para obtener los datos de un autor según
     * su nombre.
     *
     * @return array
     * @throws Exception
     * @author Fahibram Cárcamo
     */
    public function getWellKnownAuthors(): array
    {
        $authors_data = [];

        foreach (self::WELL_KNOWN_AUTHORS as $author_name) {
            $authors_data[] = $this->baseHttpRequest('GET', 'search/authors.json', ['query' => ['q' => $author_name]]);
        }

        return $authors_data;
    }

    /**
     * Realiza una petición HTTP a la API de Open Library para obtener los datos de todos los
     * libros de todos los autores guardados en la tabla `authors`.
     *
     * @return array
     * @throws Exception
     * @author Fahibram Cárcamo
     */
    public function getAllAhthorsBooks(): array
    {
        $authors = $this->author_repository->getAll(new Request());
        $books_data = [];

        foreach ($authors as $author) {
            $author_books_data = $this->baseHttpRequest('GET', 'search.json', ['query' => ['author_key' => $author->open_library_key]]);
            $author_books_data['author_id'] = $author->id;
            $books_data[] = $author_books_data;
        }

        return $books_data;
    }

    /**
     * Realizar una petición HTTP a la API de Open Library para obtener los datos un autor según su
     * nombre.
     *
     * @param string $author_name Nombre del autor.
     * @return array|null
     * @throws Exception
     * @author Fahibram Cárcamo
     */
    public function getAuthor(string $author_name): array|null
    {
        return $this->baseHttpRequest('GET', 'search/authors.json', ['query' => ['q' => $author_name]]);
    }

    /**
     * Realizar una petición HTTP a la API de Open Library para obtener los datos un libro según su
     * título.
     *
     * @param string $book_title Título del libro.
     * @param string $author_key Llave de Open Library del autro.
     * @return array|null
     * @throws Exception
     * @author Fahibram Cárcamo
     */
    public function getBook(string $book_title, string $author_key = ''): array|null
    {
        return $this->baseHttpRequest('GET', 'search.json', ['query' => ['title' => $book_title, 'author_key' => $author_key]]);
    }

    /**
     * Realiza una petición HTTP base a la API de Open Library.
     *
     * @param string $method Método HTTP (GET, POST, PUT, DELETE, etc.).
     * @param string $endpoint Endpoint de la API (ej: '/search/authors.json').
     * @param array $options Opciones adicionales para la petición (query, headers, json, form_params, etc.).
     * @param string|null $base_url URL base de la petición HTTP.
     * @return array|null
     * @throws Exception
     * @author Fahibram Cárcamo
     */
    private function baseHttpRequest(string $method = 'GET', string $endpoint = '', array $options = [], string $base_url = null): ?array
    {
        try {
            if (is_null($base_url)) {
                $base_url = config('app.open_library_base_url');
            }

            // Crear cliente Guzzle con configuración base.
            $client = new Client([
                'base_uri' => $base_url,
                'timeout' => 30.0,
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => 'Laravel-OpenLibrary-Client/1.0'
                ],
            ]);

            // Combinar headers personalizados si existen
            if (isset($options['headers'])) {
                $options['headers'] = array_merge(
                    ['Accept' => 'application/json', 'User-Agent' => 'Laravel-OpenLibrary-Client/1.0'],
                    $options['headers']
                );
            }

            // Realizar la petición HTTP
            $response = $client->request($method, $endpoint, $options);

            // Obtener el cuerpo de la respuesta
            $body = $response->getBody()->getContents();

            // Decodificar la respuesta JSON
            $data = json_decode($body, true);

            return $data;

        } catch (GuzzleException $exception) {
            // Registrar error de Guzzle
            Log::error('OpenLibrary API Guzzle Error', [
                'message' => $exception->getMessage(),
                'endpoint' => $endpoint,
                'method' => $method,
            ]);

            throw new Exception('Error al realizar la petición a Open Library: ' . $exception->getMessage());

        } catch (Exception $exception) {
            // Registrar cualquier otro error
            Log::error('OpenLibrary API General Error', [
                'message' => $exception->getMessage(),
                'endpoint' => $endpoint,
                'method' => $method,
            ]);

            throw $exception;
        }
    }
}