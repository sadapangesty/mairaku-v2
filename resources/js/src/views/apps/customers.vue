<template>
  <div>
    <div class="flex items-center justify-between flex-wrap gap-4">
      <h2 class="text-xl">Customers</h2>
      <div
        class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto"
      >
        <div class="flex gap-3">
          <div>
            <button
              type="button"
              class="btn btn-primary"
              @click="editCustomer()"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 ltr:mr-2 rtl:ml-2"
              >
                <circle
                  cx="10"
                  cy="6"
                  r="4"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  opacity="0.5"
                  d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>

              Add Customer
            </button>
          </div>
          <div>
            <button
              type="button"
              class="btn btn-outline-primary p-2"
              :class="{ 'bg-primary text-white': displayType === 'list' }"
              @click="displayType = 'list'"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
              >
                <path
                  d="M2 5.5L3.21429 7L7.5 3"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  opacity="0.5"
                  d="M2 12.5L3.21429 14L7.5 10"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  d="M2 19.5L3.21429 21L7.5 17"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  d="M22 19L12 19"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  opacity="0.5"
                  d="M22 12L12 12"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <path
                  d="M22 5L12 5"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>
            </button>
          </div>
          <div>
            <button
              type="button"
              class="btn btn-outline-primary p-2"
              :class="{ 'bg-primary text-white': displayType === 'grid' }"
              @click="displayType = 'grid'"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
              >
                <path
                  opacity="0.5"
                  d="M2.5 6.5C2.5 4.61438 2.5 3.67157 3.08579 3.08579C3.67157 2.5 4.61438 2.5 6.5 2.5C8.38562 2.5 9.32843 2.5 9.91421 3.08579C10.5 3.67157 10.5 4.61438 10.5 6.5C10.5 8.38562 10.5 9.32843 9.91421 9.91421C9.32843 10.5 8.38562 10.5 6.5 10.5C4.61438 10.5 3.67157 10.5 3.08579 9.91421C2.5 9.32843 2.5 8.38562 2.5 6.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  opacity="0.5"
                  d="M13.5 17.5C13.5 15.6144 13.5 14.6716 14.0858 14.0858C14.6716 13.5 15.6144 13.5 17.5 13.5C19.3856 13.5 20.3284 13.5 20.9142 14.0858C21.5 14.6716 21.5 15.6144 21.5 17.5C21.5 19.3856 21.5 20.3284 20.9142 20.9142C20.3284 21.5 19.3856 21.5 17.5 21.5C15.6144 21.5 14.6716 21.5 14.0858 20.9142C13.5 20.3284 13.5 19.3856 13.5 17.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  d="M2.5 17.5C2.5 15.6144 2.5 14.6716 3.08579 14.0858C3.67157 13.5 4.61438 13.5 6.5 13.5C8.38562 13.5 9.32843 13.5 9.91421 14.0858C10.5 14.6716 10.5 15.6144 10.5 17.5C10.5 19.3856 10.5 20.3284 9.91421 20.9142C9.32843 21.5 8.38562 21.5 6.5 21.5C4.61438 21.5 3.67157 21.5 3.08579 20.9142C2.5 20.3284 2.5 19.3856 2.5 17.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
                <path
                  d="M13.5 6.5C13.5 4.61438 13.5 3.67157 14.0858 3.08579C14.6716 2.5 15.6144 2.5 17.5 2.5C19.3856 2.5 20.3284 2.5 20.9142 3.08579C21.5 3.67157 21.5 4.61438 21.5 6.5C21.5 8.38562 21.5 9.32843 20.9142 9.91421C20.3284 10.5 19.3856 10.5 17.5 10.5C15.6144 10.5 14.6716 10.5 14.0858 9.91421C13.5 9.32843 13.5 8.38562 13.5 6.5Z"
                  stroke="currentColor"
                  stroke-width="1.5"
                />
              </svg>
            </button>
          </div>
        </div>

        <div class="relative">
          <input
            type="text"
            placeholder="Search Customers"
            class="form-input py-2 ltr:pr-11 rtl:pl-11 peer"
            v-model="searchCustomer"
            @keyup="searchCustomers"
          />
          <div
            class="absolute ltr:right-[11px] rtl:left-[11px] top-1/2 -translate-y-1/2 peer-focus:text-primary"
          >
            <svg
              class="mx-auto"
              width="16"
              height="16"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <circle
                cx="11.5"
                cy="11.5"
                r="9.5"
                stroke="currentColor"
                stroke-width="1.5"
                opacity="0.5"
              ></circle>
              <path
                d="M18.5 18.5L22 22"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
              ></path>
            </svg>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-5 panel p-0 border-0 overflow-hidden">
      <template v-if="displayType === 'list'">
        <vue3-datatable
          :columns="columns"
          :rows="rows"
          :loading="loading"
          :search="true"
          :per-page="perPage"
          :total="total"
          :page="page"
          @update:search="onSearch"
          @update:page="onPageChange"
        />
      </template>
    </div>
    <template v-if="displayType === 'grid'">
      <div
        class="grid 2xl:grid-cols-4 xl:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-6 w-full"
      >
        <template v-for="cusomer in filterdCustomersList" :key="cusomer.id">
          <div
            class="bg-white dark:bg-[#1c232f] rounded-md overflow-hidden text-center shadow relative"
          >
            <div
              class="bg-white/40 rounded-t-md bg-[url('/assets/images/notification-bg.png')] bg-center bg-cover p-6 pb-0"
            >
              <template v-if="cusomer.path">
                <img
                  class="object-contain w-4/5 max-h-40 mx-auto"
                  :src="`/assets/images/${cusomer.path}`"
                />
              </template>
            </div>
            <div class="px-6 pb-24 -mt-10 relative">
              <div
                class="shadow-md bg-white dark:bg-gray-900 rounded-md px-2 py-4"
              >
                <div class="text-xl">{{ cusomer.name }}</div>
                <div class="text-white-dark">{{ cusomer.role }}</div>
                <div
                  class="flex items-center justify-between flex-wrap mt-6 gap-3"
                >
                  <div class="flex-auto">
                    <div class="text-info">{{ cusomer.posts }}</div>
                    <div>Posts</div>
                  </div>
                  <div class="flex-auto">
                    <div class="text-info">{{ cusomer.following }}</div>
                    <div>Following</div>
                  </div>
                  <div class="flex-auto">
                    <div class="text-info">{{ cusomer.followers }}</div>
                    <div>Followers</div>
                  </div>
                </div>
                <div class="mt-4">
                  <ul
                    class="flex space-x-4 rtl:space-x-reverse items-center justify-center"
                  >
                    <li>
                      <a
                        href="javascript:;"
                        class="btn btn-outline-primary p-0 h-7 w-7 rounded-full"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="24px"
                          height="24px"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="w-4 h-4"
                        >
                          <path
                            d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"
                          ></path>
                        </svg>
                      </a>
                    </li>
                    <li>
                      <a
                        href="javascript:;"
                        class="btn btn-outline-primary p-0 h-7 w-7 rounded-full"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="24px"
                          height="24px"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="w-4 h-4"
                        >
                          <rect
                            x="2"
                            y="2"
                            width="20"
                            height="20"
                            rx="5"
                            ry="5"
                          ></rect>
                          <path
                            d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"
                          ></path>
                          <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                      </a>
                    </li>
                    <li>
                      <a
                        href="javascript:;"
                        class="btn btn-outline-primary p-0 h-7 w-7 rounded-full"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="24px"
                          height="24px"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="w-4 h-4"
                        >
                          <path
                            d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"
                          ></path>
                          <rect x="2" y="9" width="4" height="12"></rect>
                          <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                      </a>
                    </li>
                    <li>
                      <a
                        href="javascript:;"
                        class="btn btn-outline-primary p-0 h-7 w-7 rounded-full"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="24px"
                          height="24px"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          class="w-4 h-4"
                        >
                          <path
                            d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"
                          ></path>
                        </svg>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
              <div
                class="mt-6 grid grid-cols-1 gap-4 ltr:text-left rtl:text-right"
              >
                <div class="flex items-center">
                  <div class="flex-none ltr:mr-2 rtl:ml-2">Email :</div>
                  <div class="truncate text-white-dark">
                    {{ cusomer.email }}
                  </div>
                </div>
                <div class="flex items-center">
                  <div class="flex-none ltr:mr-2 rtl:ml-2">Phone :</div>
                  <div class="text-white-dark">{{ cusomer.phone }}</div>
                </div>
                <div class="flex items-center">
                  <div class="flex-none ltr:mr-2 rtl:ml-2">Address :</div>
                  <div class="text-white-dark">{{ cusomer.location }}</div>
                </div>
              </div>
            </div>
            <div
              class="mt-6 flex gap-4 absolute bottom-0 w-full ltr:left-0 rtl:right-0 p-6"
            >
              <button
                type="button"
                class="btn btn-outline-primary w-1/2"
                @click="editCustomer(cusomer)"
              >
                Edit
              </button>
              <button
                type="button"
                class="btn btn-outline-danger w-1/2"
                @click="deleteCustomer(cusomer)"
              >
                Delete
              </button>
            </div>
          </div>
        </template>
      </div>
    </template>

    <!-- add cusomer modal -->
    <TransitionRoot appear :show="addCustomerModal" as="template">
      <Dialog
        as="div"
        @close="addCustomerModal = false"
        class="relative z-[51]"
      >
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-[black]/60" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center px-4 py-8">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg text-black dark:text-white-dark"
              >
                <button
                  type="button"
                  class="absolute top-4 ltr:right-4 rtl:left-4 text-gray-400 hover:text-gray-800 dark:hover:text-gray-600 outline-none"
                  @click="addCustomerModal = false"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="w-6 h-6"
                  >
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </button>
                <div
                  class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                >
                  {{ params.id ? "Edit Customer" : "Add Customer" }}
                </div>
                <div class="p-5">
                  <form @submit.prevent="saveCustomer">
                    <div class="mb-5">
                      <label for="name">Name</label>
                      <input
                        id="name"
                        type="text"
                        placeholder="Enter Name"
                        class="form-input"
                        v-model="params.name"
                      />
                    </div>
                    <div class="mb-5">
                      <label for="email">Email</label>
                      <input
                        id="email"
                        type="email"
                        placeholder="Enter Email"
                        class="form-input"
                        v-model="params.email"
                      />
                    </div>
                    <div class="mb-5">
                      <label for="number">Phone Number</label>
                      <input
                        id="number"
                        type="text"
                        placeholder="Enter Phone Number"
                        class="form-input"
                        v-model="params.phone"
                      />
                    </div>
                    <div class="mb-5">
                      <label for="occupation">Occupation</label>
                      <input
                        id="occupation"
                        type="text"
                        placeholder="Enter Occupation"
                        class="form-input"
                        v-model="params.role"
                      />
                    </div>
                    <div class="mb-5">
                      <label for="address">Address</label>
                      <textarea
                        id="address"
                        rows="3"
                        placeholder="Enter Address"
                        class="form-textarea resize-none min-h-[130px]"
                        v-model="params.location"
                      ></textarea>
                    </div>
                    <div class="flex justify-end items-center mt-8">
                      <button
                        type="button"
                        class="btn btn-outline-danger"
                        @click="addCustomerModal = false"
                      >
                        Cancel
                      </button>
                      <button
                        type="submit"
                        class="btn btn-primary ltr:ml-4 rtl:mr-4"
                      >
                        {{ params.id ? "Update" : "Add" }}
                      </button>
                    </div>
                  </form>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>
<script lang="ts" setup>
import { ref, onMounted,watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay,
} from "@headlessui/vue";
import Swal from "sweetalert2";
import { useMeta } from "@/composables/use-meta";
import Vue3Datatable from '@bhplugin/vue3-datatable';
import "@bhplugin/vue3-datatable/dist/style.css";
useMeta({ title: "Customers" });

const defaultParams = ref({
  id: null,
  name: "",
  email: "",
  role: "",
  phone: "",
  location: "",
});
const displayType = ref("list");
const addCustomerModal = ref(false);
const params = ref(JSON.parse(JSON.stringify(defaultParams.value)));
const filterdCustomersList: any = ref([]);
const searchCustomer = ref("");
const columns = [
  { label: "id", field: "id" },
  { label: "Name", field: "name" },
  { label: "Email", field: "email" },
];

const rows = ref([]);
const loading = ref(false);
const total = ref(0);
const perPage = ref(10);
const page = ref(1);
const cusomerList = ref([]);

const fetchData = async () => {
  loading.value = true;
  try {
    const res = await fetch(
      `/api/customers?per_page=${perPage.value}&page=${page.value}&search=${searchCustomer.value}`
    );
    const cusomerList = await res.json();
    rows.value = cusomerList.data;
    total.value = cusomerList.total;
  } catch (err) {
    console.error("Failed to fetch", err);
  } finally {
    loading.value = false;
  }
};

watch([page, searchCustomer], fetchData, { immediate: true });

function onSearch(val) {
  searchCustomer.value = val;
  page.value = 1;
}

function onPageChange(val) {
  page.value = val;
}

const editCustomer = (customer: any = null) => {
  params.value = JSON.parse(JSON.stringify(defaultParams.value));
  if (customer) {
    params.value = JSON.parse(JSON.stringify(customer));
  }

  addCustomerModal.value = true;
};

const saveCustomer = () => {
  if (!params.value.name) {
    showMessage("Name is required.", "error");
    return true;
  }
  if (!params.value.email) {
    showMessage("Email is required.", "error");
    return true;
  }
  if (!params.value.phone) {
    showMessage("Phone is required.", "error");
    return true;
  }
  if (!params.value.role) {
    showMessage("Occupation is required.", "error");
    return true;
  }

  if (params.value.id) {
    //update customer
    let customer: any = cusomerList.value.find((d) => d.id === params.value.id);
    customer.name = params.value.name;
    customer.email = params.value.email;
    customer.role = params.value.role;
    customer.phone = params.value.phone;
    customer.location = params.value.location;
  } else {
    //add customer
    let maxCustomerId = cusomerList.value.length
      ? cusomerList.value.reduce(
          (max, character) => (character.id > max ? character.id : max),
          cusomerList.value[0].id
        )
      : 0;

    let customer = {
      id: maxCustomerId + 1,
      path: "profile-35.png",
      name: params.value.name,
      email: params.value.email,
      role: params.value.role,
      phone: params.value.phone,
      location: params.value.location,
      posts: 20,
      followers: "5K",
      following: 500,
    };
    cusomerList.value.splice(0, 0, customer);
    searchCustomers();
  }

  showMessage("Customer has been saved successfully.");
  addCustomerModal.value = false;
};

const deleteCustomer = (customer: any = null) => {
  cusomerList.value = cusomerList.value.filter((d) => d.id != customer.id);
  searchCustomers();
  showMessage("Customer has been deleted successfully.");
};

const showMessage = (msg = "", type = "success") => {
  const toast: any = Swal.mixin({
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: 3000,
    customClass: { container: "toast" },
  });
  toast.fire({
    icon: type,
    title: msg,
    padding: "10px 20px",
  });
};
</script>
