<template>
  <div>
    <div>
      <div class="flex md:items-center md:flex-row flex-col mb-5 gap-5">
        <h5 class="font-semibold text-lg dark:text-white-light">Data Barang</h5>
        <div class="ltr:ml-auto rtl:mr-auto flex items-center gap-2">
          <button type="button" class="btn btn-primary" @click="openAddModal()">
            Add Barang
          </button>
          <button type="button" class="btn btn-secondary" @click="toggleView">
            {{ isGridView ? 'Table View' : 'Grid View' }}
          </button>
          <input v-model="search" type="text" class="form-input w-auto" placeholder="Search..." />
        </div>
      </div>


      <div v-if="isGridView" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div
          v-for="item in filteredRows"
          :key="item.id"
          class="bg-white dark:bg-gray-800 rounded-xl shadow p-3 flex flex-col"
        >
          <img
            :src="item.foto ? `/assets/images/${item.foto}` : '/assets/images/default-product.png'"
            class="w-full h-40 object-cover rounded mb-2"
          />
          <div class="flex-1">
            <h4 class="font-semibold text-base mb-1">{{ item.nama_barang }}</h4>
            <p class="text-xs text-gray-500 mb-1">Kategori: {{ item.kategori?.nama || '-' }}</p>
            <p class="text-xs text-gray-500 mb-1">Jenis: {{ item.jenis?.nama || '-' }}</p>
            <p class="text-sm font-semibold text-primary">Rp {{ Number(item.harga).toLocaleString() }}</p>
          </div>
          <div class="flex justify-between mt-2">
            <button class="btn btn-xs btn-outline-primary" @click="openEditModal(item)">✏️ Edit</button>
            <button class="btn btn-xs btn-outline-danger" @click="deleteBarang(item)">❌ Delete</button>
          </div>
        </div>
      </div>


      <div v-else class="datatable">
        <vue3-datatable
          :rows="filteredRows"
          :columns="cols"
          :search="search"
          :totalRows="filteredRows.length"
          :sortable="true"
          sortColumn="no"
          skin="whitespace-nowrap bh-table-hover"
        >
          <template #foto="data">
            <img
              :src="data.value.foto ? `/assets/images/${data.value.foto}` : '/assets/images/default-product.png'"
              class="w-9 h-9 rounded-full"
            />
          </template>
          <template #kategori="data">
            {{ data.value.kategori?.nama || '-' }}
          </template>
          <template #jenis="data">
            {{ data.value.jenis?.nama || '-' }}
          </template>
          <template #actions="data">
            <div class="flex items-center gap-2">
              <button type="button" class="btn btn-sm btn-outline-primary p-1" @click="openEditModal(data.value)" title="Edit">✏️</button>
              <button type="button" class="btn btn-sm btn-outline-danger p-1" @click="deleteBarang(data.value)" title="Delete">❌</button>
            </div>
          </template>
        </vue3-datatable>
      </div>
    </div>

    <!-- Modal tetap -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-lg">
        <h3 class="text-lg font-semibold mb-4">{{ isEditing ? 'Edit Barang' : 'Tambah Barang' }}</h3>
        <div class="grid gap-4">
          <input v-model="form.nama_barang" type="text" placeholder="Nama Barang" class="form-input" />

          <select v-model="form.id_kategori" class="form-input">
            <option value="">Pilih Kategori</option>
            <option v-for="item in kategoris" :key="item.id_kategori" :value="item.id_kategori">{{ item.nama }}</option>
          </select>

          <select v-model="form.id_jenis" class="form-input">
            <option value="">Pilih Jenis</option>
            <option v-for="item in jeniss" :key="item.id_jenis" :value="item.id_jenis">{{ item.nama }}</option>
          </select>

          <input v-model="form.sku" type="text" placeholder="SKU" class="form-input" disabled />

          <input v-model="form.harga" type="number" placeholder="Harga" class="form-input" />

          <input v-model="form.link_barang" type="text" placeholder="Link Barang" class="form-input" />

          <input type="file" accept="image/*" class="form-input" @change="handleImageUpload" />
          <div v-if="imagePreview" class="mt-2">
            <img :src="imagePreview" class="w-20 h-20 object-cover rounded-full" />
          </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
          <button class="btn btn-primary" @click="saveBarang">{{ isEditing ? 'Update' : 'Save' }}</button>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useMeta } from '@/composables/use-meta';

useMeta({ title: 'Data Barang' });

const isGridView = ref(false);
const toggleView = () => {
  isGridView.value = !isGridView.value;
};

const search = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const loading = ref(false);
const imagePreview = ref('');

const kategoris = ref<any[]>([]);
const jeniss = ref<any[]>([]);
const rows = ref<any[]>([]);

const form = ref({
  id: null as number | null,
  nama_barang: '',
  id_kategori: '',
  id_jenis: '',
  sku: '', // optional, biasanya di-generate backend
  harga: '',
  link_barang: '',
  foto: '',
});

const cols = ref([
  { field: 'no', title: 'No', sortable: false },
  { field: 'foto', title: 'Foto', sortable: false },
  { field: 'nama_barang', title: 'Nama Barang', sortable: true },
  { field: 'kategori', title: 'Kategori', sortable: false },
  { field: 'jenis', title: 'Jenis', sortable: false },
  { field: 'sku', title: 'SKU', sortable: true },
  { field: 'harga', title: 'Harga', sortable: true },
  { field: 'link_barang', title: 'Link Barang', sortable: false },
  { field: 'actions', title: 'Action', sortable: false },
]);

const filteredRows = computed(() =>
  rows.value.filter(item =>
    item.nama_barang.toLowerCase().includes(search.value.toLowerCase())
    || item.sku.toLowerCase().includes(search.value.toLowerCase())
    || (item.kategori?.nama || '').toLowerCase().includes(search.value.toLowerCase())
    || (item.jenis?.nama || '').toLowerCase().includes(search.value.toLowerCase())
  )
);

const loadBarangs = async () => {
  try {
    const response = await axios.get('/api/barangs');
    rows.value = response.data.data.data.map((item: any, index: number) => ({
      ...item,
      no: index + 1,
    }));
  } catch (error) {
    console.error('Gagal memuat data:', error);
    Swal.fire('Error', 'Gagal memuat data barang.', 'error');
  }
};

const loadKategoris = async () => {
  try {
    const res = await axios.get('/api/kategoris');
    kategoris.value = res.data;
  } catch (error) {
    console.error('Gagal memuat kategori:', error);
  }
};

const loadJeniss = async () => {
  try {
    const res = await axios.get('/api/jenis');
    jeniss.value = res.data;
  } catch (error) {
    console.error('Gagal memuat jenis:', error);
  }
};

onMounted(() => {
  loadBarangs();
  loadKategoris();
  loadJeniss();
});

const openAddModal = () => {
  isEditing.value = false;
  imagePreview.value = '';
  form.value = {
    id: null,
    nama_barang: '',
    id_kategori: '',
    id_jenis: '',
    sku: '',
    harga: '',
    link_barang: '',
    foto: '',
  };
  showModal.value = true;
};

const openEditModal = (barang: any) => {
  isEditing.value = true;
  form.value = {
    id: barang.id,
    nama_barang: barang.nama_barang,
    id_kategori: barang.id_kategori?.toString() || '',
    id_jenis: barang.id_jenis?.toString() || '',
    sku: barang.sku,
    harga: barang.harga,
    link_barang: barang.link_barang,
    foto: barang.foto,
  };
  imagePreview.value = barang.foto ? `/assets/images/${barang.foto}` : '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveBarang = async () => {
  loading.value = true;
  try {
    const payload = new FormData();
    payload.append('nama_barang', form.value.nama_barang);
    payload.append('id_kategori', form.value.id_kategori || '');
    payload.append('id_jenis', form.value.id_jenis || '');
    payload.append('harga', form.value.harga || '0');
    payload.append('link_barang', form.value.link_barang || '');
    if (form.value.sku) {
      payload.append('sku', form.value.sku);
    }
    if (form.value.foto instanceof File) {
      payload.append('foto', form.value.foto);
    }

    if (isEditing.value && form.value.id) {
      await axios.post(`/api/barangs/${form.value.id}?_method=PUT`, payload);
    } else {
      await axios.post('/api/barangs', payload);
    }

    Swal.fire('Success', 'Data berhasil disimpan!', 'success');
    await loadBarangs();
    closeModal();
  } catch (error: any) {
    console.error('Save error:', error);
    const msg = error.response?.data?.errors
      ? Object.values(error.response.data.errors).flat().join('<br>')
      : 'Gagal menyimpan data';
    Swal.fire('Error', msg, 'error');
  } finally {
    loading.value = false;
  }
};

const deleteBarang = async (barang: any) => {
  if (!barang.id) return Swal.fire('Error', 'ID tidak ditemukan', 'error');
  const confirm = await Swal.fire({
    title: `Hapus barang "${barang.nama_barang}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  });
  if (!confirm.isConfirmed) return;

  loading.value = true;
  try {
    await axios.delete(`/api/barangs/${barang.id}`);
    Swal.fire('Deleted!', 'Barang berhasil dihapus.', 'success');
    await loadBarangs();
  } catch (error) {
    console.error('Delete error:', error);
    Swal.fire('Error', 'Gagal menghapus barang.', 'error');
  } finally {
    loading.value = false;
  }
};

const handleImageUpload = (e: any) => {
  const file = e.target.files[0];
  if (file) {
    form.value.foto = file;
    const reader = new FileReader();
    reader.onload = (ev) => {
      imagePreview.value = ev.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const handleSaveBarang = async () => {
  try {
    // Tampilkan loading
    Swal.fire({
      title: 'Menyimpan...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // Simpan ke backend
    const res = await axios.post('/api/barangs', form.value);

    // Tutup modal
    showBarangModal.value = false;

    // Tambahkan ke katalog (atau refresh catalog)
    catalog.value.push({
      id: res.data.id,
      title: res.data.nama_barang,
      price: res.data.harga,
      image: res.data.foto_url || 'https://via.placeholder.com/100'
    });

    // Reset form
    form.value = {
      nama_barang: '',
      id_kategori: '',
      id_jenis: '',
      sku: '',
      harga: '',
      link_barang: ''
    };

    // Tampilkan alert sukses
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Barang berhasil disimpan.',
      timer: 2000,
      showConfirmButton: false
    });

  } catch (e) {
    console.error('Gagal menyimpan barang:', e);
    // Alert error
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: 'Barang gagal disimpan, cek console untuk detail.'
    });
  }
};

</script>


