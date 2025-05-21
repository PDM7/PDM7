import { writable } from 'svelte/store'

export function persistStore(key, initial) {
  if (typeof window === 'undefined') {
    return writable(initial)
  }

  const persist = localStorage.getItem(key)
  const persistedData = persist ? JSON.parse(persist) : null

  // Se já existe, mantém. Se não, define o timeStamp agora.
  const data = persistedData || {
    ...initial,
    timeStamp: Date.now(),
  }

  const store = writable(data)

  store.subscribe(value => {
    if (typeof window !== 'undefined') {
      localStorage.setItem(key, JSON.stringify(value))
    }
  })

  return store
}
