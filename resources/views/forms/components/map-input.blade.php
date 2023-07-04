<x-dynamic-component :component="$getFieldWrapperView()" :id="$getId()" :label="$getLabel()" :label-sr-only="$isLabelHidden()" :helper-text="$getHelperText()"
    :hint="$getHint()" :hint-action="$getHintAction()" :hint-color="$getHintColor()" :hint-icon="$getHintIcon()" :required="$isRequired()" :state-path="$getStatePath()">
    <div x-data="async () => {
        @if($hasCss())
        if (!document.getElementById('map-picker-css')) {
            const link = document.createElement('link');
            link.id = 'map-picker-css';
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = '{{ $cssUrl() }}';
            link.media = 'all';
            document.head.appendChild(link);
        }
        @endif
        @if($hasJs())
        if (!document.getElementById('map-picker-js')) {
            const script = document.createElement('script');
            script.id = 'map-picker-js';
            script.src = '{{ $jsUrl() }}';
            document.head.appendChild(script);
        }
        @endif
        do {
            await (new Promise(resolve => setTimeout(resolve, 100)));
        } while (window.mapPicker === undefined);
        const m = mapPicker($wire, {{ $getMapConfig() }});
        m.attach($refs.map);
    }" wire:ignore>
        <div x-ref="map" class="w-full" style="min-height: 30vh; z-index: 1 !important;">
        </div>
    </div>
</x-dynamic-component>
