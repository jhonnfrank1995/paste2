<?php

declare(strict_types=1);

namespace App\Core;

/**
* Encapsula la respuesta HTTP.
*/
class Response
{
public function setStatusCode(int $code): self
{
http_response_code($code);
return $this;
}

public function setHeader(string $name, string $value): self
{
header("{$name}: {$value}");
return $this;
}

public function redirect(string $url, int $statusCode = 302): void
{
$this->setStatusCode($statusCode);
header("Location: {$url}");
exit();
}

public function json(array $data, int $status = 200, array $headers = []): void
{
$this->setStatusCode($status);
$this->setHeader('Content-Type', 'application/json; charset=utf-8');
foreach ($headers as $name => $value) {
$this->setHeader($name, $value);
}
echo json_encode($data);
exit();
}

public function plain(string $text, int $status = 200): void
{
$this->setStatusCode($status);
$this->setHeader('Content-Type', 'text/plain; charset=utf-8');
echo $text;
exit();
}
}