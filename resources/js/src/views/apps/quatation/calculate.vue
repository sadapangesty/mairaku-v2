<template>
  <div>
    <div class="panel p-6">
      <h5 class="font-semibold text-lg mb-4 dark:text-white-light">Quotation Calculator</h5>

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <div class="font-semibold mb-1">Barang (Yuan)</div>
          <input v-model.number="form.total_barang_rmb" type="number" class="form-input" />
        </div>

        <div>
          <div class="font-semibold mb-1">Tipe Pengiriman</div>
          <select v-model="form.jenis_pengiriman" class="form-input">
            <option disabled value="">Pilih Tipe Pengiriman</option>
            <option value="barang_kecil_laut">Barang Kecil - Laut</option>
            <option value="barang_besar_laut">Barang Besar - Laut</option>
            <option value="barang_kecil_udara">Barang Kecil - Udara</option>
          </select>
        </div>

        <!-- Barang Besar Laut -->
        <template v-if="form.jenis_pengiriman === 'barang_besar_laut'">
          <div>
            <div class="font-semibold mb-1">Panjang (cm)</div>
            <input v-model.number="form.panjang" type="number" class="form-input" />
          </div>
          <div>
            <div class="font-semibold mb-1">Lebar (cm)</div>
            <input v-model.number="form.lebar" type="number" class="form-input" />
          </div>
          <div>
            <div class="font-semibold mb-1">Tinggi (cm)</div>
            <input v-model.number="form.tinggi" type="number" class="form-input" />
          </div>
          <div>
            <div class="font-semibold mb-1">Hasil P×L×T (m³)</div>
            <input v-model="cbm" type="number" readonly class="form-input bg-gray-100" />
          </div>
        </template>

        <!-- Barang Kecil Udara -->
        <template v-if="form.jenis_pengiriman === 'barang_kecil_udara'">
          <div>
            <div class="font-semibold mb-1">Berat (kg)</div>
            <input v-model.number="form.berat" type="number" class="form-input" />
          </div>
        </template>

        <div>
          <div class="font-semibold mb-1">Kurs Sell</div>
          <input v-model="kursSell" type="number" readonly class="form-input bg-gray-100" />
        </div>

        <div>
          <div class="font-semibold mb-1">Harga Total (Preview)</div>
          <input :value="previewTotal.toLocaleString()" type="text" readonly class="form-input bg-gray-100" />
        </div>

        <div>
          <div class="font-semibold mb-1">Asuransi (%)</div>
          <input v-model="asuransiPercent" type="number" readonly class="form-input bg-gray-100" />
        </div>
        <div>
          <div class="font-semibold mb-1">Margin (%)</div>
          <input v-model="marginPercent" type="number" readonly class="form-input bg-gray-100" />
        </div>

        <template v-if="form.jenis_pengiriman === 'barang_kecil_laut'">
          <div>
            <div class="font-semibold mb-1">Biaya Pengiriman Laut (%)</div>
            <input v-model="biayaPengirimanLautPercent" type="number" readonly class="form-input bg-gray-100" />
          </div>
        </template>

        <template v-if="form.jenis_pengiriman === 'barang_besar_laut'">
          <div>
            <div class="font-semibold mb-1">Harga Jual / CBM</div>
            <input v-model="hargaJualCbm" type="number" readonly class="form-input bg-gray-100" />
          </div>
        </template>

        <template v-if="form.jenis_pengiriman === 'barang_kecil_udara'">
          <div>
            <div class="font-semibold mb-1">Harga Jual / KG</div>
            <input v-model="hargaJualKg" type="number" readonly class="form-input bg-gray-100" />
          </div>
        </template>
      </div>

      <div class="flex justify-end mt-4">
        <button class="btn btn-primary" @click="calculate">Hitung Total</button>
      </div>

      <div v-if="total !== null" class="mt-4 p-4 bg-green-100 rounded text-green-800">
        <strong>Total Quotation (API):</strong> {{ total.toLocaleString() }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const total = ref(null);
const kursSell = ref(0);
const asuransiPercent = ref(0);
const marginPercent = ref(0);
const biayaPengirimanLautPercent = ref(0);
const hargaJualCbm = ref(0);
const hargaJualKg = ref(0);
const cbm = ref(0);

const form = ref({
  total_barang_rmb: '',
  jenis_pengiriman: 'barang_kecil_laut',
  panjang: '',
  lebar: '',
  tinggi: '',
  berat: '',
});

// Hitung CBM realtime
watch([() => form.value.panjang, () => form.value.lebar, () => form.value.tinggi], () => {
  if (form.value.jenis_pengiriman === 'barang_besar_laut') {
    const { panjang, lebar, tinggi } = form.value;
    cbm.value = panjang && lebar && tinggi ? (panjang * lebar * tinggi) / 1000000 : 0;
  } else {
    cbm.value = 0;
  }
});

// ✅ Preview total realtime sesuai rumus
const previewTotal = computed(() => {
  const rmb = form.value.total_barang_rmb || 0;
  const kurs = kursSell.value || 0;
  const asuransi = (asuransiPercent.value || 0) / 100;
  const margin = (marginPercent.value || 0) / 100;
  const biayaLaut = (biayaPengirimanLautPercent.value || 0) / 100;
  const cbmRate = hargaJualCbm.value || 0;
  const kgRate = hargaJualKg.value || 0;

  const hargaSatuan = rmb * kurs;
  const hargaAsuransi = hargaSatuan * asuransi;
  const hargaSementara = hargaSatuan + hargaAsuransi;
  const hargaMargin = hargaSementara * margin;
  const hargaSetelahMargin = hargaSementara + hargaMargin;

  if (form.value.jenis_pengiriman === 'barang_kecil_laut') {
    const biayaPengiriman = hargaSetelahMargin * biayaLaut;
    return hargaSetelahMargin + biayaPengiriman;
  }

  if (form.value.jenis_pengiriman === 'barang_kecil_udara') {
    const berat = form.value.berat || 0;
    return hargaSetelahMargin + (berat * kgRate);
  }

  if (form.value.jenis_pengiriman === 'barang_besar_laut') {
    return hargaSetelahMargin + (cbm.value * cbmRate);
  }

  return hargaSetelahMargin;
});


// Ambil data awal
onMounted(async () => {
  try {
    const [settingRes, kursRes, logistikusRes] = await Promise.all([
      axios.get('/api/quotation-settings'),
      axios.get('/api/currency-rates?type=sell'),
      axios.get('/api/logistikus'),
    ]);

    kursSell.value = kursRes.data[0]?.rate || 0;
    const setting = settingRes.data[0];
    asuransiPercent.value = setting.asuransi_percent;
    marginPercent.value = setting.margin_percent;
    biayaPengirimanLautPercent.value = setting.biaya_pengiriman_laut_percent;

    const laut = logistikusRes.data.find(l => l.jenis === 'laut');
    const udara = logistikusRes.data.find(l => l.jenis === 'udara');
    if (laut) hargaJualCbm.value = laut.harga_jual;
    if (udara) hargaJualKg.value = udara.harga_jual;

  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal memuat data awal', 'error');
  }
});

// Tombol Hitung Total pakai backend (opsional)
const calculate = async () => {
  try {
    const res = await axios.post('/api/quotation/calculate', form.value);
    total.value = res.data.total;
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal menghitung total', 'error');
  }
};
</script>
