<template>
  <div>
    <div class="panel pb-0 mt-6">
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Supplier</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary w-full sm:flex-1" @click="openAddModal()">
            Add Supplier
          </button>
          <input v-model="search" type="text" class="form-input w-full sm:flex-1" placeholder="Search..." />
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
          <template #actions="data">
            <div class="flex items-center gap-2">
              <button type="button" class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)" title="Edit">✏️</button>
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteSupplier(data.value)" title="Delete">❌</button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Supplier' : 'Tambah Supplier' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.nama" type="text" placeholder="Nama Supplier" class="form-input" />
          <select v-model="form.tipe" class="form-input">
            <option value="online">Online</option>
            <option value="lokal">Lokal</option>
          </select>

          <template v-if="form.tipe === 'online'">
            <input v-model="form.platform" type="text" placeholder="Platform (Tokopedia, Taobao, dll)" class="form-input" />
            <input v-model="form.link" type="url" placeholder="Link toko (https://...)" class="form-input" />
          </template>
          <template v-else-if="form.tipe === 'lokal'">
            <input v-model="form.alamat" type="text" placeholder="Alamat" class="form-input" />
            <input v-model="form.telepon" type="text" placeholder="Telepon" class="form-input" />
          </template>
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveSupplier">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Supplier' });

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);

const form = ref({
  id_supplier: null as number | null,
  nama: '',
  tipe: 'online',
  platform: '',
  link: '',
  alamat: '',
  telepon: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'nama', title: 'Nama', sortable: true },
  { field: 'tipe', title: 'Tipe', sortable: true },
  { field: 'platform', title: 'Platform', sortable: true },
  { field: 'link', title: 'Link', sortable: true },
  { field: 'alamat', title: 'Alamat', sortable: true },
  { field: 'telepon', title: 'Telepon', sortable: true },
  { field: 'actions', title: 'Action', sortable: false },
]);

const rows = ref<any[]>([]);

// Methods
const loadSuppliers = async () => {
  try {
    const res = await axios.get('/api/suppliers');
    rows.value = res.data.map((item: any, idx: number) => ({ ...item, no: idx + 1 }));
  } catch (error) {
    console.error(error);
    Swal.fire('Error', 'Gagal memuat data supplier.', 'error');
  }
};

const openAddModal = () => {
  isEditing.value = false;
  form.value = { id_supplier: null, nama: '', tipe: 'online', platform: '', link: '', alamat: '', telepon: '' };
  showModal.value = true;
};

const openEditModal = (supplier: any) => {
  isEditing.value = true;
  form.value = { ...supplier };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveSupplier = async () => {
  loading.value = true;
  try {
    if (isEditing.value) {
      await axios.put(`/api/suppliers/${form.value.id_supplier}`, form.value);
      Swal.fire('Success', 'Data supplier diperbarui!', 'success');
    } else {
      await axios.post('/api/suppliers', form.value);
      Swal.fire('Success', 'Supplier baru ditambahkan!', 'success');
    }
    await loadSuppliers();
    closeModal();
  } catch (error: any) {
    console.error(error);
    const msg = error.response?.data?.errors
      ? Object.values(error.response.data.errors).flat().join('<br>')
      : 'Gagal menyimpan data';
    Swal.fire('Error', msg, 'error');
  } finally {
    loading.value = false;
  }
};

const deleteSupplier = async (supplier: any) => {
  if (!supplier.id_supplier) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
  const confirm = await Swal.fire({
    title: `Hapus supplier "${supplier.nama}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  });
  if (!confirm.isConfirmed) return;
  try {
    await axios.delete(`/api/suppliers/${supplier.id_supplier}`);
    Swal.fire('Deleted!', 'Supplier berhasil dihapus.', 'success');
    await loadSuppliers();
  } catch (error) {
    console.error(error);
    Swal.fire('Error', 'Gagal menghapus supplier.', 'error');
  }
};

// Init
onMounted(() => loadSuppliers());
</script>
