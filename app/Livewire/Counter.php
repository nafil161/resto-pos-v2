<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function decrement(): void
    {
        $this->count = max(0, $this->count - 1);
    }

    // public function reset(): void
    // {
    //     $this->count = 0;
    // }

    public function render()
    {
        return view('livewire.counter');
    }
}
