<?php

namespace App\Components;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class TextInput implements Htmlable
{
    protected string $label;

    public function __construct(protected string $name)
    {
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function label(string $label): self
    {

        $this->label = $label;

        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label ?? str($this->name)->replace('_', ' ')->title();
    }

    public function render(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.text-input', $this->extractPublicMethods());
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    public function extractPublicMethods(): array
    {
        $methods = [];

        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $methods[$method->getName()] = \Closure::fromCallable([$this, $method->getName()]);
        }
        return $methods;
    }
}
