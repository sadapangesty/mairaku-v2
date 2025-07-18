<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Logistik</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button class="btn btn-primary" @click="openAddModal">Add Logistik</button>
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
          skin="whitespace-nowrap bh-table-hover"
        >
          <template #address="data">
            <div class="whitespace-normal break-words">{{ data.value.address }}</div>
          </template>
          <template #actions="data">
            <div class="flex gap-2">
              <button class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)">✏️</button>
              <button class="btn btn-sm btn-outline-danger p-1" @click="deleteLogistic(data.value)">❌</button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit' : 'Tambah' }} Logistik</h3>
        <div class="grid gap-3">
          <input v-model="form.name" type="text" placeholder="Nama" class="form-input" />

          <select v-model="form.type" class="form-input">
            <option disabled value="">Pilih Tipe</option>
            <option value="Local">Local</option>
            <option value="Internasional">Internasional</option>
          </select>

          <select v-model="form.service" class="form-input">
            <option disabled value="">Pilih Service</option>
            <option
              v-for="opt in filteredServices"
              :key="opt.value"
              :value="opt.value"
            >
              {{ opt.label }}
            </option>
          </select>

          <input v-model="form.phone" type="text" placeholder="Telepon" class="form-input" />
          <input v-model="form.website" type="text" placeholder="Website" class="form-input" />
          <textarea v-model="form.address" placeholder="Alamat" class="form-input"></textarea>
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveLogistic">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Logistik' });

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);

const form = ref({
  id_logistic: null,
  name: '',
  type: '',
  service: '',
  phone: '',
  website: '',
  address: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'name', title: 'Nama', sortable: true },
  { field: 'type', title: 'Tipe', sortable: true },
  { field: 'service', title: 'Service', sortable: true },
  { field: 'phone', title: 'Telepon', sortable: true },
  { field: 'website', title: 'Website', sortable: true },
  { field: 'address', title: 'Alamat', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const rows = ref([]);

const serviceOptions = [
  { value: 'Reguler', label: 'Reguler', type: 'Local' },
  { value: 'Trucking', label: 'Trucking', type: 'Local' },
  { value: 'Sameday', label: 'Sameday', type: 'Local' },
  { value: 'Instant', label: 'Instant', type: 'Local' },
  { value: 'LCL', label: 'LCL', type: 'Internasional' },
  { value: 'FCL', label: 'FCL', type: 'Internasional' },
];

const filteredServices = computed(() => {
  if (form.value.type === 'Local') {
    return serviceOptions.filter(opt => opt.type === 'Local');
  }
  if (form.value.type === 'Internasional') {
    return serviceOptions.filter(opt => opt.type === 'Internasional');
  }
  return [];
});

// reset service jika type berubah
watch(() => form.value.type, () => {
  form.value.service = '';
});

const loadLogistics = async () => {
  try {
    const res = await axios.get('/api/logistics');
    rows.value = res.data.map((item, idx) => ({ ...item, no: idx + 1 }));
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal memuat data logistik', 'error');
  }
};
onMounted(loadLogistics);

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id_logistic: null, name: '', type: '', service: '', phone: '', website: '', address: '' };
  showModal.value = true;
};

const openEditModal = (data) => {
  isEditing.value = true;
  form.value = { ...data };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveLogistic = async () => {
  try {
    if (isEditing.value) {
      await axios.put(`/api/logistics/${form.value.id_logistic}`, form.value);
      Swal.fire('Success', 'Logistik diperbarui!', 'success');
    } else {
      await axios.post('/api/logistics', form.value);
      Swal.fire('Success', 'Logistik ditambahkan!', 'success');
    }
    await loadLogistics();
    closeModal();
  } catch (e) {
    console.error(e);
    const msg = e.response?.data?.errors
      ? Object.values(e.response.data.errors).flat().join('<br>')
      : 'Gagal menyimpan';
    Swal.fire('Error', msg, 'error');
  }
};

const deleteLogistic = async (data) => {
  const confirm = await Swal.fire({ title: 'Hapus?', icon: 'warning', showCancelButton: true });
  if (!confirm.isConfirmed) return;
  try {
    await axios.delete(`/api/logistics/${data.id_logistic}`);
    Swal.fire('Deleted!', '', 'success');
    await loadLogistics();
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal menghapus', 'error');
  }
};
</script>
