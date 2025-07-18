<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Logistikku</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button class="btn btn-primary" @click="openAddModal()">Tambah Logistikku</button>
          <input v-model="search" type="text" class="form-input w-auto" placeholder="Search..." />
        </div>
      </div>
      <div class="datatable">
        <vue3-datatable
          :rows="rows"
          :columns="cols"
          :search="search"
          :totalRows="rows.length"
          :sortable="true"
          sortColumn="no"
          skin="bh-table-hover"
        >
          <template #vendor="data">
            <div class="whitespace-normal break-words">
              {{ data.value.vendor_name }} / {{ data.value.vendor_service }}
            </div>
          </template>
          <template #actions="data">
            <div class="flex items-center gap-2">
              <button class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)">✏️</button>
              <button class="btn btn-sm btn-outline-danger p-1" @click="deleteLogistiku(data.value)">❌</button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Logistikku' : 'Tambah Logistikku' }}</h3>
        <div class="grid gap-4">
          <!-- Nama logistiku -->
          <input v-model="form.nama_logistiku" type="text" placeholder="Nama Logistikku" class="form-input" />
          <!-- Pilih vendor -->
          <select v-model="form.id_logistic" class="form-input">
            <option value="">Pilih Vendor</option>
            <option v-for="v in vendors" :key="v.id_logistic" :value="v.id_logistic">
              {{ v.name }} / {{ v.service }}
            </option>
          </select>
          <!-- Jenis -->
          <select v-model="form.jenis" class="form-input">
            <option value="">Pilih Jenis</option>
            <option value="laut">Laut</option>
            <option value="udara">Udara</option>
          </select>
          <input v-model="form.harga_vendor" type="number" placeholder="Harga Vendor" class="form-input" />
          <input v-model="form.harga_jual" type="number" placeholder="Harga Jual" class="form-input" />
          <input v-model="form.tanggal_aktif" type="date" class="form-input" />
          <input v-model="form.tanggal_nonaktif" type="date" class="form-input" />
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveLogistiku">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Logistikku' });

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const rows = ref([]);
const vendors = ref([]);

const form = ref({
  id_logistiku: null,
  nama_logistiku: '',
  id_logistic: '',
  jenis: '',
  harga_vendor: '',
  harga_jual: '',
  tanggal_aktif: '',
  tanggal_nonaktif: ''
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'kode', title: 'Kode', sortable: true },
  { field: 'nama_logistiku', title: 'Nama Logistikku', sortable: true },
  { field: 'vendor', title: 'Vendor / Service', sortable: false },
  { field: 'jenis', title: 'Jenis', sortable: true },
  { field: 'harga_vendor', title: 'Harga Vendor', sortable: true },
  { field: 'harga_jual', title: 'Harga Jual', sortable: true },
  { field: 'tanggal_aktif', title: 'Tgl Aktif', sortable: true },
  { field: 'tanggal_nonaktif', title: 'Tgl Nonaktif', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const loadLogistikus = async () => {
  try {
    const res = await axios.get('/api/logistikus');
    rows.value = res.data.map((item, idx) => ({
      ...item,
      no: idx + 1,
      kode: item.kode, // tampilkan kode
      vendor_name: item.logistic?.name || '',
      vendor_service: item.logistic?.service || ''
    }));
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal memuat data logistikku', 'error');
  }
};

const loadVendors = async () => {
  try {
    const res = await axios.get('/api/logistics');
    vendors.value = res.data;
  } catch (e) {
    console.error(e);
  }
};

onMounted(() => {
  loadLogistikus();
  loadVendors();
});

const openAddModal = () => {
  isEditing.value = false;
  form.value = {
    id_logistiku: null,
    nama_logistiku: '',
    id_logistic: '',
    jenis: '',
    harga_vendor: '',
    harga_jual: '',
    tanggal_aktif: '',
    tanggal_nonaktif: ''
  };
  showModal.value = true;
};

const openEditModal = (data) => {
  isEditing.value = true;
  form.value = {
    id_logistiku: data.id_logistiku,
    nama_logistiku: data.nama_logistiku,
    id_logistic: data.id_logistic,
    jenis: data.jenis,
    harga_vendor: data.harga_vendor,
    harga_jual: data.harga_jual,
    tanggal_aktif: data.tanggal_aktif,
    tanggal_nonaktif: data.tanggal_nonaktif
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveLogistiku = async () => {
  try {
    if (isEditing.value) {
      await axios.put(`/api/logistikus/${form.value.id_logistiku}`, form.value);
      Swal.fire('Success', 'Data updated!', 'success');
    } else {
      await axios.post('/api/logistikus', form.value);
      Swal.fire('Success', 'Data added!', 'success');
    }
    await loadLogistikus();
    closeModal();
  } catch (e) {
    console.error(e);
    const msg = e.response?.data?.errors
      ? Object.values(e.response.data.errors).flat().join('<br>')
      : 'Gagal';
    Swal.fire('Error', msg, 'error');
  }
};

const deleteLogistiku = async (data) => {
  if (!data.id_logistiku) return Swal.fire('Error', 'ID not found', 'error');
  const confirm = await Swal.fire({ title: 'Delete?', icon: 'warning', showCancelButton: true });
  if (!confirm.isConfirmed) return;
  try {
    await axios.delete(`/api/logistikus/${data.id_logistiku}`);
    Swal.fire('Deleted!', '', 'success');
    await loadLogistikus();
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Failed to delete', 'error');
  }
};
</script>

<style>
td[data-col="vendor"] {
  white-space: normal !important;
  word-break: break-word;
}
</style>
