@props(['id' => 'dataTable', 'rowspan' => false, 'colspan' => false])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-bordered']) }} id="{{ $id }}" width="100%"
        cellspacing="0">
        <thead>
            <tr {{ $rowspan ? 'rowspan="' . $rowspan . '"' : '' }} {{ $colspan ? 'colspan="' . $colspan . '"' : '' }}>
                {{ $header }}
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
