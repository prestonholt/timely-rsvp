<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ title }}
      </h2>
    </template>
    <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
      <div class="max-w-xl text-sm text-gray-600">{{ description }}</div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'

export default {
  components: {
    AppLayout,
  },

  props: {
    status: Number,
  },
  computed: {
    title() {
      return {
        503: 'Service Unavailable',
        500: 'Server Error',
        404: 'Page Not Found',
        403: 'Forbidden',
        490: 'Expired',
        491: 'Event Ended',
      }[this.status]
    },
    description() {
      return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
        490: 'Sorry, it looks like this invitation has expired.',
        491: 'Sorry, it looks like this event has already ended.',
      }[this.status]
    },
  },
}
</script>