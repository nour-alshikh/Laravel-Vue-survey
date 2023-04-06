import axios from "axios";
import store from "./store";

import.meta.env.VITE_API_BASE_URL

const axiosClient = axios.create({
  baseURL: `http://localhost:8000/api`
})


axiosClient.interceptors.request.use(config => {
  config.headers.Authorization = `Bearer ${store.state.user.token}`
  return config
})

export default axiosClient
