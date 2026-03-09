<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-nova']) }}>
    {{ $slot }}
</button>
