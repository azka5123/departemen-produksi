@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('main-content')
    <x-form.card title="Edit Data Transaksi">
        <x-form.form method="POST" route='transaksi.update' id="{{ $transaction->id }}">
            @csrf
            {{-- @method('PUT') --}}
            <x-form.input type="text" name="kode" label="Nomor Transaksi" placeholder="Nomor Transaksi" id="kode"
                readonly="true" value="{{ $transaction->kode }}" />
            <x-form.input type="date" name="tgl" label="Tanggal" id="tgl" value="{{ $transaction->tgl }}" />

            <x-form.select name="customer" id="customer" label="Customer" :options="$customers->map->display->all()"
                selected="{{ $transaction->rCostumer->id }}" />
            <x-form.input type="number" name="telp" label="Nomor HP" placeholder="Nomor HP" id="telp"
                readonly="true" value="{{ $transaction->rCostumer->telp }}" />

            <x-form.select name="barang" id="barang" label="Barang" :options="$barangs->map->display->all()" multiple="true" />

            <x-form.table id="selected-items-table" class="align-middle text-center">
                @slot('header')
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kode Barang</th>
                    <th rowspan="2">Nama Barang</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">Harga Bandrol</th>
                    <th colspan="2">Diskon</th>
                    <th rowspan="2">Harga Diskon</th>
                    <th rowspan="2">Total</th>
                    <tr>
                        <th>%</th>
                        <th>(Rp)</th>
                    </tr>
                @endslot
            </x-form.table>

            <x-form.button label="Update Transaksi" />
        </x-form.form>
    </x-form.card>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const customers = @json($customers);
            const barangs = @json($barangs);
            const transactionDetails = @json($transactionDetails);

            // Initialize Select2 for customer and barang
            $('#customer').select2({
                theme: 'bootstrap4',
                placeholder: '--- Pilih ---',
            });

            $('#barang').select2({
                theme: 'bootstrap4',
                placeholder: '--- Pilih ---',
                multiple: true,
            });

            // Populate initial selected barang items
            $('#barang').val(@json($transaction->rDetails->pluck('id_barang')->all())).trigger('change');

            $('#customer').on('change', function() {
                const selectedCustomer = $(this).val();
                $('#telp').val(selectedCustomer && customers[selectedCustomer] ? customers[selectedCustomer]
                    .telp : '');
            });

            function parseCurrency(value) {
                return value ? parseFloat(value.replace(/[^0-9,]/g, '').replace(',', '.')) || 0 : 0;
            }

            function formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value);
            }

            function updateInputValue(element) {
                element.val(formatCurrency(parseCurrency(element.val())));
            }


            $('#barang').on('change', function() {
                const selectedIds = new Set($('#barang').val());
                const existingIds = new Set();

                $('#selected-items-table tbody tr').each(function() {
                    const id = $(this).find('.jumlah-input').attr('id');
                    if (id) {
                        const itemId = id.replace('jumlah-', '');
                        if (!selectedIds.has(itemId)) {
                            $(this).remove(); // Remove rows for deselected items
                        } else {
                            existingIds.add(itemId);
                        }
                    }
                });

                // Add rows for newly selected items
                selectedIds.forEach(id => {
                    if (!existingIds.has(id) && barangs[id]) {
                        const barang = barangs[id];
                        const diskonRp = (barang.harga * barang.diskon_pct) / 100;
                        const hargaDiskon = barang.harga - diskonRp;
                        const qty = transactionDetails[id] ? transactionDetails[id].qty : 0;
                        const harga = transactionDetails[id] ? transactionDetails[id].total_harga :
                            0;

                        const row = `
                    <tr>
                        <td>${id}</td>
                        <td>${barang.kode}</td>
                        <td>${barang.nama}</td>
                        <td><x-form.input type="text" name="jumlah[${id}]" id="jumlah-${id}" data-harga-diskon="${hargaDiskon}" class="jumlah-input" value="${qty}"/></td>
                        <td>${formatCurrency(barang.harga)}</td>
                        <td>${barang.diskon_pct}%</td>
                        <td><x-form.input type="text" name="diskonRp[${id}]" id="diskonRp-${id}" value="${formatCurrency(diskonRp)}" readonly="true" /></td>
                        <td><x-form.input type="text" name="hargaDiskon[${id}]" id="hargaDiskon-${id}" value="${formatCurrency(hargaDiskon)}" readonly="true" /></td>
                        <td><x-form.input type="text" name="total[${id}]" id="total-${id}" value="${formatCurrency(harga)}" readonly="true" class="total-cell" data-id="${id}" /></td>
                    </tr>`;

                        const subtotalRow = $('#selected-items-table tbody tr.subtotal-row');
                        if (subtotalRow.length > 0) {
                            subtotalRow.before(row);
                        } else {
                            $('#selected-items-table tbody').append(row);
                        }
                    }
                });

                if ($('#selected-items-table tbody tr.subtotal-row').length === 0) {
                    const totalsRow = `
                <tr class="subtotal-row">
                    <td colspan="7" class="border border-0"></td>
                    <td colspan="2">
                        <p class="fw-bold text-left">Subtotal: <x-form.input type="text" name="subtotal" id="subtotal" readonly="true"/> </p>
                        <p class="fw-bold text-left">Diskon: <x-form.input type="text" name="diskon" id="diskon" value="${formatCurrency({{ $transaction->diskon }})}"/></p>
                        <p class="fw-bold text-left">Ongkir: <x-form.input type="text" name="ongkir" id="ongkir" value="${formatCurrency({{ $transaction->ongkir }})}"/></p>
                        <p class="fw-bold text-left">Total: <x-form.input type="text" name="total_bayar" id="total_bayar" readonly="true" /></p>
                    </td>
                </tr>`;
                    $('#selected-items-table tbody').append(totalsRow);
                }

                initializeEventListeners();
                updateTotals();
            });

            function initializeEventListeners() {
                console.log('Initializing event listeners...');
                $('.jumlah-input').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    const id = $(this).attr('id').replace('jumlah-', '');
                    const jumlah = parseFloat($(this).val()) || 0;
                    const hargaDiskon = parseFloat($(this).data('harga-diskon'));
                    const total_bayar = jumlah * hargaDiskon;

                    $(`.total-cell[data-id="${id}"]`).val(formatCurrency(total_bayar));
                    updateTotals();
                });

                $('#diskon, #ongkir').on('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    updateTotals();
                }).on('blur', function() {
                    updateInputValue($(this));
                });
            }

            function updateTotals() {
                console.log('Updating totals...');
                let subtotal = 0;

                $('.total-cell').each(function() {
                    subtotal += parseCurrency($(this).val());
                });

                const diskon = parseCurrency($('#diskon').val()) || 0;
                const ongkir = parseCurrency($('#ongkir').val()) || 0;
                const total_bayar = subtotal - diskon + ongkir;

                $('#subtotal').val(formatCurrency(subtotal));
                $('#total_bayar').val(formatCurrency(total_bayar));
            }

            if ($('#barang').val().length > 0) {
                $('#barang').trigger('change');
            }
        });
    </script>
@endpush
