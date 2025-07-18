<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Logistikku</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button class="btn btn-primary" @click="openAddModal()">Add Logistik</button>
          <input v-model="search" type="text" class="form-input w-auto" placeholder="Search..." />
        </div>
      </div>
      <div class="datatable">
        <vue3-datatable
          :rows="filteredRows"
          :columns="cols"
          :search="search"
          :totalRows="filteredRows.length"
          :sortable="true"
          sortColumn="no"
          skin="bh-table-hover"
        >
          <template #vendor="data">
            {{ data.value.vendor?.nama_vendor || '-' }}
          </template>
          <template #type="data">
            {{ data.value.type === 'laut' ? 'Laut' : 'Udara' }}
          </template>
          <template #active="data">
            <span :class="{'text-green-500': data.value.active, 'text-red-500': !data.value.active}">
              {{ data.value.active ? 'Aktif' : 'Nonaktif' }}
            </span>
          </template>
          <template #actions="data">
            <div class="flex items-center gap-2">
              <button class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)">✏️</button>
              <button class="btn btn-sm btn-outline-danger p-1" @click="deleteLogistik(data.value)">❌</button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Logistik' : 'Tambah Logistik' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.nama_logistikku" type="text" placeholder="Nama Logistikku" class="form-input" />
          <select v-model="form.vendor_id" class="form-input">
            <option value="">Pilih Vendor</option>
            <option v-for="v in vendors" :key="v.id" :value="v.id">{{ v.nama_vendor }}</option>
          </select>
          <select v-model="form.type" class="form-input">
            <option value="">Pilih Tipe Pengiriman</option>
            <option value="laut">Laut</option>
            <option value="udara">Udara</option>
          </select>
          <input v-model="form.harga_vendor" type="number" placeholder="Harga Vendor" class="form-input" />
          <input v-model="form.harga_jual" type="number" placeholder="Harga Jual" class="form-input" />
          <label class="flex items-center gap-2">
            <input type="checkbox" v-model="form.active" /> Aktif
          </label>
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveLogistik">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Logistikku' });

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const vendors = ref([]);
const rows = ref([]);

const form = ref({
  id: null,
  nama_logistikku: '',
  vendor_id: '',
  type: '',
  harga_vendor: '',
  harga_jual: '',
  active: true,
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'nama_logistikku', title: 'Nama Logistikku', sortable: true },
  { field: 'vendor', title: 'Nama Vendor', sortable: false },
  { field: 'type', title: 'Tipe', sortable: true },
  { field: 'harga_vendor', title: 'Harga Vendor', sortable: true },
  { field: 'harga_jual', title: 'Harga Jual', sortable: true },
  { field: 'active', title: 'Status', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const loadLogistics = async () => {
  try {
    const res = await axios.get('/api/logistics');
    rows.value = res.data.map((item, idx) => ({
      ...item,
      no: idx + 1,
    }));
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal memuat data logistik', 'error');
  }
};

const loadVendors = async () => {
  try {
    const res = await axios.get('/api/vendors');
    vendors.value = res.data;
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal memuat data vendor', 'error');
  }
};

onMounted(() => {
  loadLogistics();
  loadVendors();
});

const openAddModal = () => {
  isEditing.value = false;
  form.value = {
    id: null,
    nama_logistikku: '',
    vendor_id: '',
    type: '',
    harga_vendor: '',
    harga_jual: '',
    active: true,
  };
  showModal.value = true;
};

const openEditModal = (data) => {
  isEditing.value = true;
  form.value = {
    id: data.id,
    nama_logistikku: data.nama_logistikku,
    vendor_id: data.vendor_id,
    type: data.type,
    harga_vendor: data.harga_vendor,
    harga_jual: data.harga_jual,
    active: data.active,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveLogistik = async () => {
  try {
    if (isEditing.value && form.value.id) {
      await axios.put(`/api/logistics/${form.value.id}`, form.value);
      Swal.fire('Success', 'Logistik updated!', 'success');
    } else {
      await axios.post('/api/logistics', form.value);
      Swal.fire('Success', 'Logistik added!', 'success');
    }
    await loadLogistics();
    closeModal();
  } catch (e) {
    console.error(e);
    const msg = e.response?.data?.errors
      ? Object.values(e.response.data.errors).flat().join('<br>')
      : 'Gagal';
    Swal.fire('Error', msg, 'error');
  }
};

const deleteLogistik = async (data) => {
  if (!data.id) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
  const confirm = await Swal.fire({ title: 'Delete?', icon: 'warning', showCancelButton: true });
  if (!confirm.isConfirmed) return;
  try {
    await axios.delete(`/api/logistics/${data.id}`);
    Swal.fire('Deleted!', '', 'success');
    await loadLogistics();
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal menghapus', 'error');
  }
};

const filteredRows = computed(() =>
  rows.value.filter(item =>
    item.nama_logistikku.toLowerCase().includes(search.value.toLowerCase())
    || (item.vendor?.nama_vendor || '').toLowerCase().includes(search.value.toLowerCase())
    || item.type.toLowerCase().includes(search.value.toLowerCase())
  )
);
</script>
