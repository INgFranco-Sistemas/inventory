<script setup>
import { PERMISOS } from '@/utils/constants';
import { VRadioGroup, VSelect } from 'vuetify/components';

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDialogVisible',
  'addUser'
])

const name = ref(null);
const surname = ref(null);
const email = ref(null);
const phone = ref(null);
const type_document = ref('DNI');
const n_document = ref(null);
const role_id = ref(null);
const sucursale_id = ref(null);
const gender = ref(null);
const password = ref(null);

const warning = ref(null);
const error_exits = ref(null);
const success = ref(null);

const store = async() => {
  warning.value = null;
  error_exits.value = null;
  success.value = null;
  if(!name.value){
    setTimeout(() =>{
      warning.value = "Se debe llenar el nombre del rol";
    }, 50);
    return;
  }

  if(permissions.value.length == 0){
    setTimeout(() =>{
      warning.value = "Se debe seleccionar al menos un permiso";
    }, 50);
    return;
  }

  let data = {
    name: name.value,
    permissions: permissions.value,
  }

  try {
    const resp = await $api("users", {
      method: 'POST',
      body: data,
      onResponseError({response}){
        error_exits.value = response._data.error;
      }
    })
    console.log(resp);
    if(resp.message == 403){
      error_exits.value = resp.message_text;
    }else{
      success.value = "El usuario se ha registrado correctamente";
      emit("addUser", resp.user);
      name.value = '';
      warning.value = null;
      error_exits.value = null;
      success.value = null;
      onFormReset();
    }
  } catch (error) {
    console.log(error);
  }
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
            Registrar Usuario
          </h4>
        </div>

        <!-- 👉 Form -->
        <VForm
          class="mt-4"
          @submit.prevent="store"
        >
          <VRow>
            <!-- 👉 First Name -->
            <VCol
              cols="6"
            >
              <VTextField
                v-model="name"
                label="Nombre"
                placeholder="Example: José"
              />
            </VCol>

            <VCol
              cols="6"
            >
              <VTextField
                v-model="surname"
                label="Apellidos"
                placeholder="Example: mendoza"
              />
            </VCol>
            
            <VCol
              cols="6"
            >
              <VTextField
                v-model="email"
                label="Correo"
                placeholder="Example: laravest@gmail.com"
              />
            </VCol>

            <VCol
              cols="6"
            >
              <VTextField
                v-model="phone"
                type="number"
                label="Teléfono"
                placeholder="Example: 999999999"
              />
            </VCol>

            <VCol
              cols="6"
            >
              <VSelect 
                :items="[
                  'DNI',
                  'RUC',
                  'PASAPORTE',
                  'CARNET DE EXTRANJERIA',
                  'TARJETA MILITAR'
                ]"
                v-model="type_document"
                label="Tipo de Documento"
                placeholder="Select Item"
                eager
              />
            </VCol>

            <VCol
              cols="6"
            >
              <VTextField
                v-model="n_document"
                type="number"
                label="N° de documento"
                placeholder="Example: 999999999"
              />
            </VCol>

            <VCol
              cols="6"
            >
              <VSelect 
                :items="[]"
                v-model="role_id"
                label="Rol"
                placeholder="Select Item"
                eager
              />
            </VCol>

            <VCol
              cols="6"
            >
              <VSelect 
                :items="[]"
                v-model="sucursale_id"
                label="Sucursal"
                placeholder="Select Item"
                eager
              />
            </VCol>

            <VCol
              cols="6"
            >
              <VRadioGroup v-model="gender">
                <VRadio 
                  label="Masculino"
                  value="M"
                />
                <VRadio 
                  label="Femenino"
                  value="F"
                />
              </VRadioGroup>
            </VCol>

            <VCol
              cols="6"
            >
              <VFileInput label="Avatar" />
            </VCol>

            <VCol
              cols="6"
            >
              <VTextField
                v-model="password"
                type="password"
                label="Contraseña"
                placeholder="*******"
              />
            </VCol>

            <VCol
              cols="12"
              v-if="warning"
            >
              <VAlert
                closable
                close-label="Close Alert"
                color="warning"
              >
                {{ warning }}
              </VAlert>
            </VCol>

            <VCol
              cols="12"
              v-if="error_exits"
            >
              <VAlert
                closable
                close-label="Close Alert"
                color="error"
              >
                {{ error_exits }}
              </VAlert>
            </VCol>

            <VCol
              cols="12"
              v-if="success"
            >
              <VAlert
                closable
                close-label="Close Alert"
                color="success"
              >
                {{ success }}
              </VAlert>
            </VCol>

            <VCol
              cols="12"
            >
              
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
