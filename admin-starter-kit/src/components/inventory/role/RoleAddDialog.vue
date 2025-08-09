<script setup>
import { PERMISOS } from '@/utils/constants';

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
])

const name = ref(null);
console.log(PERMISOS);
const permissions = ref([]);

const AddEditPermissionDialog = (permission) => {
  let INDEX = permissions.value.findIndex((perm) => perm == permission);
  if(INDEX != -1) {
    permissions.value.splice(INDEX,1);
  }else{
    permissions.value.push(permission);
  }
  console.log(permissions);
}

const onFormSubmit = () => {
  emit('update:isDialogVisible', false)
  emit('submit', userData.value)
}

const onFormReset = () => {
  emit('update:isDialogVisible', false)
}

const dialogVisibleUpdate = val => {
  emit('update:isDialogVisible', val)
}
</script>

<template>
  <VDialog
    max-width="650"
    :model-value="props.isDialogVisible"
    @update:model-value="dialogVisibleUpdate"
  >
    <VCard class="pa-sm-11 pa-3">
      <!-- 👉 dialog close btn -->
      <DialogCloseBtn
        variant="text"
        size="default"
        @click="onFormReset"
      />

      <VCardText class="pt-5">
        <div class="text-center pb-6">
          <h4 class="text-h4 mb-2">
            Add Role
          </h4>
        </div>

        <!-- 👉 Form -->
        <VForm
          class="mt-4"
          @submit.prevent="onFormSubmit"
        >
          <VRow>
            <!-- 👉 First Name -->
            <VCol
              cols="12"
            >
              <VTextField
                v-model="name"
                label="Nombre"
                placeholder="Example: Admin"
              />
            </VCol>

            <VCol
              cols="12"
            >
              <VTable>
                <thead>
                  <tr>
                    <th class="text-uppercase">
                      Modulo
                    </th>
                    <th class="text-uppercase">
                      Permisos
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <tr
                    v-for="(item,index) in PERMISOS"
                    :key="index"
                  >
                    <td>
                      {{ item.name }}
                    </td>
                    <td>
                      <ul>
                        <li v-for="(permiso, index2) in item.permisos" :key="index2" style="list-style: none;">
                          <VCheckbox
                            :label="permiso.name"
                            :value="permiso.permiso"
                            @click="AddEditPermissionDialog(permiso.permiso)"
                          />
                        </li>
                      </ul>
                    </td>
                  </tr>
                </tbody>
              </VTable>
            </VCol>

            <!-- 👉 Submit and Cancel -->
            <VCol
              cols="12"
              class="d-flex flex-wrap justify-center gap-4"
            >
              <VBtn type="submit">
                Guardar
              </VBtn>

              <VBtn
                color="secondary"
                variant="outlined"
                @click="onFormReset"
              >
                Cancel
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
