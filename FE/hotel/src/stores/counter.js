import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    email: '',
    token: '',
    role: '', // Menyimpan role pengguna
    thecounter: 0
  }),
  actions: {
    increment() {
      this.thecounter++
    },
    logout() {
      this.email = ''
      this.token = ''
      this.role = ''
      this.thecounter = 0
    }
  }
})
