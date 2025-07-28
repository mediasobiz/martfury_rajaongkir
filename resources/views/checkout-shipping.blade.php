@php
    // Terima dari parent: $storeId dan $totalWeight
    $storeId = $storeId ?? 0;
    $totalWeight = $totalWeight ?? 0;
@endphp

<div class="rajaongkir-box" data-storeid="{{ $storeId }}">
    <label for="province-{{ $storeId }}">Provinsi</label>
    <select id="province-{{ $storeId }}" class="form-control province-select" data-storeid="{{ $storeId }}"></select>
    
    <label for="city-{{ $storeId }}" class="mt-2">Kota/Kabupaten</label>
    <select id="city-{{ $storeId }}" class="form-control city-select" data-storeid="{{ $storeId }}"></select>
    
    <label for="courier-{{ $storeId }}" class="mt-2">Kurir</label>
    <select id="courier-{{ $storeId }}" class="form-control">
        <option value="jne">JNE</option>
        <option value="pos">POS</option>
        <option value="tiki">TIKI</option>
    </select>
    
    <input type="hidden" name="origin" value="{{ setting('rajaongkir_origin_city') }}">
    <input type="hidden" name="weight" value="{{ $totalWeight }}">
    <button class="btn btn-primary mt-2 check-ongkir-btn" data-storeid="{{ $storeId }}">Cek Ongkir</button>
    <div class="ongkir-results mt-2" id="ongkir-results-{{ $storeId }}"></div>
</div>

<!-- SISIPKAN SCRIPT INI DI BAWAH FORM AGAR PASTI JALAN -->
<script>
jQuery(function($) {
    // LOG DEBUG
    console.log('RajaOngkir script loaded!');

    $('.rajaongkir-box').each(function() {
        var storeId = $(this).data('storeid');
        var $province = $('#province-' + storeId);
        var $city = $('#city-' + storeId);
        var $courier = $(this).find('select[id^="courier"]');
        var $ongkirResult = $('#ongkir-results-' + storeId);
        var $checkOngkirBtn = $(this).find('.check-ongkir-btn');
        var $origin = $(this).find('input[name="origin"]');
        var $weight = $(this).find('input[name="weight"]');

        // Load Provinsi
        $.get('/ajax/rajaongkir/provinces', function(res) {
            console.log('Provinces API Response:', res);
            let options = '<option value="">Pilih Provinsi</option>';
            if (res.data && res.data.length) {
                res.data.forEach(function(p) {
                    options += `<option value="${p.id}">${p.name}</option>`;
                });
            }
            $province.html(options);
            $city.html('<option value="">Pilih Kota</option>');
        });

        // Load Kota jika Provinsi dipilih
        $province.on('change', function() {
            let provId = $(this).val();
            if (provId) {
                $.get('/ajax/rajaongkir/cities', {province_id: provId}, function(res) {
                    console.log('Cities API Response:', res);
                    let options = '<option value="">Pilih Kota</option>';
                    if (res.data && res.data.length) {
                        res.data.forEach(function(c) {
                            options += `<option value="${c.id}">${c.name}</option>`;
                        });
                    }
                    $city.html(options);
                });
            } else {
                $city.html('<option value="">Pilih Kota</option>');
            }
        });

        // Cek ongkir
        $checkOngkirBtn.on('click', function(e) {
            e.preventDefault();
            var origin = $origin.val();
            var destination = $city.val();
            var weight = $weight.val();
            var courier = $courier.val();

            if (!origin || !destination || !weight || !courier) {
                alert('Lengkapi data pengiriman!');
                return;
            }

            $.post('/ajax/rajaongkir/cost', {
                origin: origin,
                destination: destination,
                weight: weight,
                courier: courier,
                _token: '{{ csrf_token() }}'
            }, function(r) {
                console.log('Cost API Response:', r);
                let html = '';
                if (r.meta && r.meta.code == 200 && r.data && r.data.length > 0) {
                    html = '<ul>';
                    r.data.forEach(function(service) {
                        html += `<li>${service.service}: Rp ${service.cost[0].value} (${service.cost[0].etd} hari)</li>`;
                    });
                    html += '</ul>';
                } else {
                    html = r.meta && r.meta.message ? r.meta.message : 'Ongkir tidak ditemukan.';
                }
                $ongkirResult.html(html);
            });
        });
    });
});
</script>
