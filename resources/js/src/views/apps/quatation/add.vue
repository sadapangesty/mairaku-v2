<template>
  <div class="panel pb-4 mt-2">
    <!-- Header Panel -->
    <div class="mb-4.5 px-5">
      <div class="flex flex-wrap lg:flex-nowrap items-start justify-between w-full">
        <!-- Kiri: Logo -->
        <div class="flex flex-col items-center lg:items-start mb-2 lg:w-1/4 w-full">
          <img src="/assets/images/logo.svg" alt="Logo" class="w-20 mb-6" />
          <div class="flex items-center space-x-2">
            <label for="number" class="text-md">No Quotation</label>
            <input
              id="number"
              type="text"
              class="form-input w-30"
              placeholder="#8801"
              v-model="params.invoiceNo"
            />
          </div>
        </div>

        <!-- Tengah: Judul & Alamat -->
        <div class="text-center mb-6 lg:w-1/2 w-full">
          <div class="text-2xl font-semibold text-black dark:text-white">QUOTATION</div>
           <div class="flex justify-between items-center">
          </div>
          <div class="space-y-1 mt-2 text-gray-500 dark:text-gray-400">
            <div>Jl. Lingkungan III GG LINGGA 5, RT.4/RW.3, Kota Adm, Kec. Kalideres,</div>
            <div>Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11820</div>
            <div>Mairaku@gmail.com</div>
            <div>+62882-8974-9869</div>
          </div>
        </div>

        <!-- Kanan: Quotation Info -->
        <div class="space-y-2 lg:w-1/4 w-full">

          <div class="flex justify-between items-center">
            <label for="startDate" class="mb-0"> Tanggal Quotation </label>
            <input id="startDate" type="date" class="form-input w-40" v-model="params.invoiceDate" />
          </div>
          <div class="flex justify-between items-center">
            <label for="dueDate" class="mb-0">Tanggal Berlaku</label>
            <input id="dueDate" type="date" class="form-input w-40" v-model="params.dueDate" />
          </div>
          <div class="flex justify-between items-center">
            <label class="mb-0">Pilih Pengiriman</label>
            <select class="form-select w-40" v-model="params.shippingMethod">
              <option value="">-- Pilih --</option>
              <option value="laut_kecil">Laut Kecil</option>
              <option value="laut_besar">Laut Besar</option>
              <option value="udara">Udara</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <hr class="border-[#e0e6ed] dark:border-[#1b2e4b] my-5" />

    <!-- Bill To & Payment Details Section -->
    <div class="mt-4 px-4 flex flex-col lg:flex-row justify-between gap-6">
      <!-- Bill To -->
      <div class="lg:w-1/2">
        <div class="flex items-center justify-between mb-2">
          <div class="text-lg font-semibold">Bill To :-</div>
          <button type="button" class="btn btn-warning px-2 py-1 text-xs" @click="showCustomerModal = true">+ Customer</button>

          <!-- Modal Customer -->
            <FormModalCustomer
                v-if="showCustomerModal"
                :showModal="showCustomerModal"
                :isEditing="false"
                :form="customerForm"

                @close="handleCancelCustomer"
                @save="handleSaveCustomer"
              />

        </div>
        <div class="space-y-2">
          <div class="flex items-center">
            <label class="w-1/3 mr-2 mb-0">Name</label>
            <select class="form-select flex-1" v-model="selectedCustomerId" @change="fetchCustomerData">
              <option value="">-- Pilih Pelanggan --</option>
              <option v-for="customer in customers" :key="customer.id_customer" :value="String(customer.id_customer)">
                {{ customer.name }}
              </option>
            </select>
          </div>
          <div class="flex items-center">
            <label class="w-1/3 mr-2 mb-0">Alamat</label>
            <input type="text" class="form-input flex-1" placeholder="Alamat" v-model="params.to.address" />
          </div>
          <div class="flex items-center">
            <label class="w-1/3 mr-2 mb-0">Telepon</label>
            <input type="text" class="form-input flex-1" placeholder="Telepon" v-model="params.to.phone" />
          </div>
        </div>
      </div>


      <!-- Payment Details -->
      <div class="lg:w-1/2">
        <div class="text-lg font-semibold mb-2">Payment Details :-</div>
        <div class="space-y-2">
          <div class="flex items-center">
            <label class="w-1/3 mr-2 mb-0">Nama Akun</label>
            <select class="form-select flex-1" v-model="selectedBankId" @change="fetchBankData">
              <option value="">-- Pilih Bank --</option>
              <option v-for="bank in banks" :key="bank.id_bank" :value="String(bank.id_bank)">
              {{ bank.atas_nama }}
              </option>
            </select>
          </div>
          <div class="flex items-center">
            <label class="w-1/3 mr-2 mb-0">Nomer Akun</label>
            <input type="text" class="form-input flex-1" placeholder="Nomer Akun" v-model="params.bankInfo.no" />
          </div>
        <div class="flex items-center">
          <label class="w-1/3 mr-2 mb-0">Metode Pembayaran</label>
          <select class="form-select flex-1" v-model="params.paymentMethod">
            <option value="">-- Pilih --</option>
            <option value="cash">Cash</option>
            <option value="downpayment">Down Payment</option>
          </select>
        </div>
        </div>
      </div>
    </div>

    <hr class="border-[#e0e6ed] dark:border-[#1b2e4b] my-2" />

    <!-- Catalog Section & Item Table -->
    <div class="mt-8 px-4">
      <!-- Catalog Section -->
      <div class="flex justify-between items-center mb-2">
        <div class="font-semibold">Katalog Barang</div>
        <div class="flex gap-2">
          <button class="btn btn-primary px-2 py-1 text-xs" @click="showBarangModal = true">+ Barang</button>
          <!-- Modal Barang -->
            <FormModalBarang
              v-if="showBarangModal"
              :showModal="showBarangModal"
              :isEditing="false"
              :form="form"
              :kategoris="kategoris"
              :jeniss="jeniss"
              :imagePreview="imagePreview"
              @close="handleCancel"
              @save="handleSaveBarang"
              @handleImageUpload="handleImageUpload"
            />
        </div>
      </div>
      <!-- Item katalog -->
        <div class="overflow-y-auto max-h-[500px] grid grid-cols-2 md:grid-cols-4 xl:grid-cols-6 gap-2 mb-4">
          <div
            v-for="item in catalog"
            :key="item.id"
            class="border rounded shadow-sm hover:shadow cursor-pointer overflow-hidden relative pt-[100%]"
            @click="selectedCustomerId ? addCatalogItem(item) : showSelectCustomerAlert()"
          >
            <img :src="item.image" alt="Item" class="absolute top-0 left-0 w-full h-full object-cover" />
          </div>
        </div>


      <!-- Item Table -->
      <div class="table-responsive">
        <table class="w-full">
          <thead>
            <tr>
              <th>No Marking</th>
              <th>Nama</th>
              <th>Foto</th>
              <th>Link</th>
              <th>Tipe Pengiriman</th>
              <th class="w-1">Quantity</th>
              <th class="w-1">Harga Asli</th>
              <th class="w-1">Harga Jual</th>
              <th>Total Jual</th>
              <th class="w-1"></th>
            </tr>
          </thead>
          <tbody>
            <template v-if="items.length === 0">
              <tr>
                <td colspan="8" class="text-center font-semibold">No Item Available</td>
              </tr>
            </template>
            <template v-else v-for="(item, i) in items" :key="i">
              <tr>
                <!-- Marking -->
                <td class="flex items-center gap-2">
                  <input
                    type="text"
                    class="form-input w-30"
                    placeholder="Marking"
                    v-model="item.marking"
                    :readonly="!item.customMarking"
                  />
                  <template v-if="items.length > 1">
                    <input
                      type="checkbox"
                      v-model="item.customMarking"
                      class="w-4 h-4 border border-gray-400 rounded checked:bg-primary focus:outline-none cursor-pointer"
                      title="Aktifkan custom marking"
                    />
                  </template>
                </td>


                <!-- Nama -->
                <td>
                  <input type="text" class="form-input min-w-[150px]" placeholder="Nama" v-model="item.name" />
                </td>

                <!-- Foto: preview bulat, kalau belum ada tampil input file -->
                <td class="flex items-center gap-2">
                  <template v-if="item.preview || item.foto">
                    <img
                      :src="item.preview || item.foto"
                      class="w-8 h-8 rounded-full object-cover cursor-pointer"
                      alt="Preview"
                      @click="openPreviewModal(item.preview || item.foto)"
                    />
                  </template>
                  <template v-else>
                    <input type="file" @change="handleItemImageUpload($event, i)" accept="image/*" />
                  </template>
                  <template v-if="previewModal.show">
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                      <div class="bg-white p-4 rounded shadow max-w-lg">
                        <img :src="previewModal.image" alt="Preview" class="max-w-full max-h-[80vh] object-contain" />
                        <div class="mt-2 text-right">
                          <button class="btn btn-danger px-2 py-1 text-xs" @click="closePreviewModal">Tutup</button>
                        </div>
                      </div>
                    </div>
                  </template>
                </td>
                                <!-- Link -->
                <td>
                  <input type="text" class="form-input min-w-[200px]" placeholder="Link" v-model="item.link" />
                </td>

                <td>
                  <select class="form-select w-40" v-model="item.shippingMethod">
                  <option value="">-- Pilih --</option>
                  <option value="laut_kecil">Laut Kecil</option>
                  <option value="laut_besar">Laut Besar</option>
                  <option value="udara">Udara</option>
                </select>
              </td>

                <!-- Qty -->
                <td>
                  <input type="number" class="form-input w-24" placeholder="Qty" v-model.number="item.quantity" min="0" />
                </td>

                <!-- Price -->
                <td>
                  <input type="number" class="form-input w-24" placeholder="Price" v-model.number="item.amount" min="0" />
                </td>

                  <!-- Sub Total -->
                <td>
                  Rp. {{ (item.amount * item.quantity).toLocaleString('id-ID') }}
                </td>

                <!-- Total -->
                <td>
                  Rp. {{ (item.amount * item.quantity).toLocaleString('id-ID') }}
                </td>

                <!-- Delete -->
                <td>
                  <button type="button" @click="removeItem(item)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <line x1="18" y1="6" x2="6" y2="18" />
                      <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                  </button>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Action & Summary Section -->
    <div class="flex justify-between sm:flex-row flex-col mt-4 px-4 gap-6">
      <!-- Left: Buttons & Shipping Form -->
      <div class="flex flex-col gap-2">
        <div class="font-semibold">Detail Pengiriman</div>
        <div class="flex gap-2 flex-wrap">
          <template v-if="params.shippingMethod === 'laut_besar'">
            <input type="number" v-model="shippingForm.p" class="form-input w-21" placeholder="P (cm)" />
            <input type="number" v-model="shippingForm.l" class="form-input w-21" placeholder="L (cm)" />
            <input type="number" v-model="shippingForm.t" class="form-input w-21" placeholder="T (cm)" />
          </template>
          <template v-else-if="params.shippingMethod === 'udara'">
            <input type="number" v-model="shippingForm.weight" class="form-input w-25" placeholder="Berat (kg)" />
          </template>
        </div>

        <div class="flex flex-wrap lg:flex-nowrap gap-2 mt-2">
          <button type="button" class="btn btn-primary" title="Add Item" @click="CatalogItem()">
            + Item
          </button>
          <button type="button" class="btn btn-success" title="Save" @click="handleSaveQuatation()">💾</button>
          <button type="button" class="btn btn-info" title="Preview">👁️</button>
          <button type="button" class="btn btn-danger" title="Batal">✖️</button>
          <button type="button" class="btn btn-warning" title="PDF">📄</button>
        </div>
      </div>

      <!-- Right: Summary -->
      <div class="sm:w-2/5">
          <div class="flex items-center justify-between mt-4 font-semibold">
            <div>SubTotal</div>
            <div class="flex items-center">
              <span class="mr-1">Rp</span>
              <input
                type="text"
                class="w-24 text-right bg-transparent border-none focus:outline-none"
                v-model="SubTotal"
                readonly>
            </div>
          </div>
          <div class="flex items-center justify-between mt-4 font-semibold">
            <div class="flex items-center gap-2">
              <!-- Checkbox transparan -->
              <input
                type="checkbox"
                v-model="pajakEnabled"
                class="w-4 h-4 appearance-none border border-gray-400 rounded checked:bg-primary focus:outline-none cursor-pointer"
              />
              <span>Pajak</span>
            </div>
            <div class="flex items-center">
              <span class="mr-6">%</span>
              <input
                type="text"
                class="w-20 text-right bg-transparent border-none focus:outline-none"
                v-model="pajakValue"
                :readonly="!pajakEnabled"
                placeholder="0"
                min="0"
              />
            </div>
          </div>
          <div class="flex items-center justify-between mt-4 font-semibold">
            <div>
              Shipping
              <span v-if="params.shippingMethod === 'laut_kecil'">Laut Kecil</span>
              <span v-else-if="params.shippingMethod === 'laut_besar'">Laut Besar</span>
              <span v-else-if="params.shippingMethod === 'udara'">Udara</span>
            </div>
            <div class="flex items-center">
              <span class="mr-1">Rp</span>
              <input
                type="text"
                class="w-24 text-right bg-transparent border-none focus:outline-none"
                v-model="Shipping"
                readonly
              >
            </div>
          </div>
          <div class="flex items-center justify-between mt-4 font-semibold">
            <div>Discount</div>
            <div class="flex items-center">
              <span class="mr-1">Rp</span>
              <input
                type="text"
                class="w-24 text-right bg-transparent border-none focus:outline-none"
                v-model="Discount"
                >
            </div>
          </div>
          <div class="flex items-center justify-between mt-4 font-semibold">
            <div>Total</div>
            <div class="flex items-center">
              <span class="mr-1">Rp</span>
              <input
                type="text"
                class="w-24 text-right bg-transparent border-none focus:outline-none"
                v-model="Total"
                placeholder="0"
                >
            </div>
          </div>

          <div v-if="params.paymentMethod === 'cash'" class="flex items-center justify-between mt-4 font-semibold">
            <div>Cash</div>
            <div class="flex items-center">
              <span class="mr-1">Rp</span>
              <input
                type="text"
                class="w-24 text-right bg-transparent border-none focus:outline-none"
                v-model="params.cashAmount"
                placeholder="0" />
            </div>
          </div>

          <!-- Down Payment -->
     <div v-else-if="params.paymentMethod === 'downpayment'" class="flex items-center justify-between mt-4 font-semibold">
        <div class="flex items-center gap-2">
          <span>Down Payment</span>
          <div class="flex items-center">
            <input
              type="text"
              class="w-16 text-right bg-transparent border-none focus:outline-none"
              v-model="params.dpPercent"
              placeholder="%" min="0" max="100" />
            <span class="ml-1">%</span>
          </div>
        </div>
        <div class="flex items-center">
          <span class="mr-1">Rp</span>
          <input
            type="text"
            class="w-24 text-right bg-transparent border-none focus:outline-none"
            v-model="params.dpAmount"
            placeholder="0" />
        </div>
      </div>

        <hr class="border-[#e0e6ed] dark:border-[#1b2e4b] my-1" />
           <div class="flex items-center justify-between mt-2 font-semibold">
            <div>Sisa Pembayaran</div>
            <div class="flex items-center">
              <span class="mr-1">Rp</span>
              <input
                type="text"
                class="w-24 text-right bg-transparent border-none focus:outline-none"
                v-model="sisa"
                placeholder="0"
                >
            </div>
          </div>

      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import FormModalBarang from '@/components/barangs/FormModal.vue';
import FormModalCustomer from '@/components/customers/FormModal.vue';

const pajakEnabled = ref(false);
const pajakValue = ref(0);
const showBarangModal = ref(false);
const showCustomerModal = ref(false);
const selectedCustomerId = ref('');
const customers = ref<any[]>([]);
const banks = ref<any[]>([]);
const selectedBankId = ref('');

watch(pajakEnabled, (newVal) => {
  if (!newVal) {
    pajakValue.value = 0;
  }
});

const generateQuotationNumber = () => {
  const date = new Date();
  const dd = String(date.getDate()).padStart(2, '0');
  const mm = String(date.getMonth() + 1).padStart(2, '0'); // bulan dimulai dari 0
  const yyyy = date.getFullYear();
  const datePart = `${dd}${mm}${yyyy}`;
  const random = String(Math.floor(1 + Math.random() * 9999)).padStart(4, '0'); // selalu 4 digit
  params.value.invoiceNo = `QT-MR/${datePart}-${random}`;
};

const fetchBankData = () => {
  const bank = banks.value.find(
    c => String(c.id_bank) === selectedBankId.value
  );
  if (bank) {
   params.value.bankInfo.name = bank.atas_nama || '';
   params.value.bankInfo.no = `${bank.no_rekening}-${bank.nama_bank}`;  // ini gabungan
  } else {
    params.value.to = { atas_nama: '', no_rekening: '', nama_bank: '' };
  }
};

const company = ref({
  name: '',
  logo: '',
  email: '',
  phone: '',
  address: ''
});

// Invoice params
const params = ref({
  invoiceNo: '',
  invoiceDate: '',
  dueDate: '',
  to: { name: '', address: '', phone: '',marking_code_prefix: '' },
  bankInfo: { no: '', name: '' },
  paymentMethod: '',
  cashAmount: null,
  dpAmount: null,
  shippingMethod: '',
});

// Barang katalog & form
const catalog = ref<any[]>([]);
const kategoris = ref<any[]>([]);
const jeniss = ref<any[]>([]);
const form = ref({
  nama_barang: '', id_kategori: '', id_jenis: '',
  sku: '', harga: '', link_barang: ''
});
const imageFile = ref<File|null>(null);
const imagePreview = ref('');

// Items di tabel quotation
const items = ref<any[]>([]);

// Shipping & total dummy
const shippingForm = ref({ p: null, l: null, t: null, weight: null });
const SubTotal = ref(0);
const total = ref(0);
const Shipping = ref(0);
const Discount = ref(0);

// Customer form untuk tambah customer baru
const customerForm = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  marking_code_prefix: '',
});

// alert pilih customer dulu
const showSelectCustomerAlert = () => {
  Swal.fire('Pilih Customer terlebih dahulu!', '', 'warning');
};

// Load data awal
onMounted(async () => {
  try {
    const [resKategori, resJenis, resBarang, resCustomer, resBanks] = await Promise.all([
      axios.get('/api/kategoris'),
      axios.get('/api/jenis'),
      axios.get('/api/barangs'),
      axios.get('/api/customers'),
      axios.get('/api/banks'),
    ]);
    kategoris.value = resKategori.data;
    jeniss.value = resJenis.data;
    customers.value = resCustomer.data;
    banks.value = resBanks.data;

    catalog.value = resBarang.data.data.data.map((barang: any) => ({
      id: barang.id,
      image: barang.foto.startsWith('http')
        ? barang.foto
        : `http://127.0.0.1:8001/assets/images/${barang.foto}`,
      title: barang.nama_barang,
      price: barang.harga,
    }));

    generateQuotationNumber();

    // Set default invoiceDate ke tanggal hari ini
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    params.value.invoiceDate = `${yyyy}-${mm}-${dd}`;
  } catch (error) {
    console.error(error);
    Swal.fire('Error', 'Gagal memuat data!', 'error');
  }
});


// Fetch customer detail saat dipilih
const fetchCustomerData = () => {
  const customer = customers.value.find(
    c => String(c.id_customer) === selectedCustomerId.value
  );
  if (customer) {
    params.value.to.name = customer.name || '';
    params.value.to.address = customer.address || '';
    params.value.to.phone = customer.phone || '';
    params.value.to.marking_prefix = customer.marking_code_prefix || '';
  } else {
    params.value.to = { name: '', address: '', phone: '', marking_prefix: '' };
  }
};



// Tambah item dari katalog
const addCatalogItem = (item: any) => {
  console.log('Adding item:', item);
  const total = items.value.length + 1;  // total item setelah tambah
  const prefix = params.value.to.marking_prefix || '';

  items.value.push({
    marking: `${prefix}/${total}.${total}`,  // contoh awal: londo telek/MR02/1.1 dst
    name: item.title,
    quantity: 1,
    amount: item.price,
    shippingMethod: 'laut_kecil', // default pengiriman
    customMarking: false      // default tidak custom
  });

  generateMarkings();  // update semua marking
};

const generateMarkings = () => {
  const prefix = params.value.to.marking_prefix || '';
  const total = items.value.length;

  items.value.forEach((item, index) => {
    if (!item.customMarking) {
      // auto generate: prefix/nomer.total
      item.marking = `${prefix}/${index+1}.${total}`;
    }
  });
};
watch(items, () => {
  generateMarkings();
}, { deep: true });

// Upload gambar
const handleImageUpload = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file) {
    imageFile.value = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

// Simpan barang baru
const handleSaveBarang = async () => {
  if (!form.value.nama_barang || !form.value.id_kategori || !form.value.id_jenis || !form.value.harga || !form.value.link_barang) {
    Swal.fire('Warning', 'Lengkapi form!', 'warning');
    return;
  }
  try {
    const formData = new FormData();
    formData.append('nama_barang', form.value.nama_barang);
    formData.append('id_kategori', form.value.id_kategori);
    formData.append('id_jenis', form.value.id_jenis);
    formData.append('harga', form.value.harga);
    formData.append('link_barang', form.value.link_barang);
    if (imageFile.value) formData.append('foto', imageFile.value);

    const res = await axios.post('/api/barangs', formData);
    const newBarang = res.data;

    items.value.push({
      marking: newBarang.sku || '',
      foto: newBarang.foto,
      title: newBarang.link_barang,
      quantity: 1,
      amount: Number(newBarang.harga)
    });

    Swal.fire('Success', 'Barang ditambahkan!', 'success');
    resetForm(); showBarangModal.value = false;
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal simpan barang', 'error');
  }
};

// Simpan customer baru
const handleSaveCustomer = async () => {
  if (!customerForm.value.name || !customerForm.value.email || !customerForm.value.phone || !customerForm.value.address) {
    Swal.fire('Warning', 'Lengkapi form!', 'warning');
    return;
  }
  try {
    const res = await axios.post('/api/customers', customerForm.value);
    customers.value.push(res.data);

    Swal.fire('Success', 'Customer berhasil disimpan!', 'success');
    customerForm.value = { name: '', email: '', phone: '', address: '' };
    showCustomerModal.value = false;
  } catch (e) {
    console.error(e);
    Swal.fire('Error', 'Gagal simpan customer', 'error');
  }
};

// Reset form barang
const resetForm = () => {
  form.value = { nama_barang: '', id_kategori: '', id_jenis: '', sku: '', harga: '', link_barang: '' };
  imageFile.value = null; imagePreview.value = '';
};

// Batal tambah barang
const handleCancel = () => {
  Swal.fire({ title: 'Batal?', text: 'Data Barang belum disimpan akan hilang.', icon: 'warning', showCancelButton: true })
    .then(r => { if (r.isConfirmed) { resetForm(); showBarangModal.value = false; } });
};
const handleCancelCustomer = () => {
  Swal.fire({
    title: 'Batal?',
    text: 'Data Customer belum disimpan akan hilang.',
    icon: 'warning',
    showCancelButton: true
  }).then(r => {
    if (r.isConfirmed) {
      customerForm.value = { name: '', email: '', phone: '', address: '' };
      showCustomerModal.value = false;
    }
  });
};

// Buka modal customer
const showCustomersModal = () => {
  showCustomerModal.value = true;
};

//priview gambar round
const handleItemImageUpload = (e: Event, index: number) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file) {
    items.value[index].imageFile = file;
    items.value[index].preview = URL.createObjectURL(file);
  }
};
const previewModal = ref({
  show: false,
  image: ''
});

const openPreviewModal = (imageUrl: string) => {
  previewModal.value.image = imageUrl;
  previewModal.value.show = true;
};
const closePreviewModal = () => {
  previewModal.value.show = false;
  previewModal.value.image = '';

};

const handleSaveQuatation = () =>{
  if (items.value.length === 0) {
    Swal.fire('Warning', 'Tambahkan minimal satu item!', 'warning');
    return;
  }

  // // Hitung SubTotal
  // SubTotal.value = items.value.reduce((sum, item) => sum + (item.amount * item.quantity), 0);

  // // Hitung Shipping
  // Shipping.value = params.value.shippingMethod === 'laut_kecil' ? 50000 :
  //                  params.value.shippingMethod === 'laut_besar' ? 100000 :
  //                  params.value.shippingMethod === 'udara' ? 150000 : 0;

  // // Hitung Total
  // const pajakAmount = pajakEnabled.value ? (SubTotal.value * pajakValue.value / 100) : 0;
  // Total.value = SubTotal.value + Shipping.value + pajakAmount - Discount.value;

  // Simpan data ke server
  const quatationData = {
    ...params.value,
    // items: items.value,
    // shipping: shippingForm.value,
    // subtotal: SubTotal.value,
    // shippingCost: Shipping.value,
    // discount: Discount.value,
    // total: Total.value
  };

  axios.post('/api/quatations', quatationData)
    .then(() => {
      Swal.fire('Success', 'Quotation berhasil disimpan!', 'success');
      // Reset form setelah simpan
      resetForm();
      items.value = [];
      params.value.invoiceNo = '';
      generateQuotationNumber();
    })
    .catch(err => {
      console.error(err);
      Swal.fire('Error', 'Gagal menyimpan quotation', 'error');
    });
}

</script>


