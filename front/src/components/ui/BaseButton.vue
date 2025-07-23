<template>
  <button
    :type="type"
    :disabled="disabled"
    :class="buttonClasses"
    @click="$emit('click')"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  type?: 'button' | 'submit' | 'reset'
  variant?: 'primary' | 'secondary'
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  type: 'button',
  variant: 'primary',
  disabled: false
})

defineEmits<{
  click: []
}>()

const buttonClasses = computed(() => {
  const baseClasses = 'w-full px-6 py-3.5 border-none rounded-lg text-base font-semibold cursor-pointer transition-all duration-200 transform'
  
  const variantClasses = {
    primary: 'bg-gradient-to-br from-primary-500 to-purple-600 text-white hover:shadow-lg hover:shadow-primary-500/40 hover:-translate-y-0.5',
    secondary: 'bg-gray-50 text-gray-600 border-2 border-gray-200 hover:bg-gray-100 hover:border-gray-300'
  }
  
  const disabledClasses = 'opacity-60 cursor-not-allowed transform-none hover:transform-none hover:shadow-none'
  
  return [
    baseClasses,
    variantClasses[props.variant],
    props.disabled ? disabledClasses : ''
  ].join(' ')
})
</script>