import { useEffect, useState } from "react";
import Navbar from "@/components/Navbar";
import { ResponseDataTipos } from "@/utils/interfaces";
import { useRouter } from "next/router";
import { CheckIcon } from "@heroicons/react/24/outline";
import Example from "@/components/Listbox";

export default function Page() {
  const [stateData, setStateData] = useState({} as ResponseDataTipos);

  const router = useRouter();

  async function salvar(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();

    const formData = new FormData(e.currentTarget);
    const data = Object.fromEntries(formData.entries());

    const res = await fetch(`http://localhost:8080/api/tipos/${router.query?.id}`, {
      method: 'POST',
      body: JSON.stringify(data)
    }).then(r => r.json())

    router.push('/tipos')
  }

  useEffect(() => {
    async function fetchData() {
      if (!router.query.id)
        return
      try {
        const res = await fetch(`http://localhost:8080/api/tipos/${router.query.id}`).then(r => r.json() as Promise<ResponseDataTipos>)
        setStateData(res);
      } catch (err) {
        console.error(err);
      }
    }
    fetchData();
  }, [router.query?.id]);

  return (
    <div className="min-h-full">
      <Navbar />
      <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div className="lg:flex lg:items-center lg:justify-between">
          <div className="min-w-0 flex-1">
            <h2 className="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
              Tipo {stateData.tpo_id ?? 'Carregando...'}
            </h2>
          </div>
        </div>
      </div>
      <main>

        <form action="#" onSubmit={salvar} method="POST" className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
            <div>
              <label htmlFor="descricao" className="block text-sm font-semibold leading-6 text-gray-900">
                Descrição
              </label>
              <div className="mt-2.5">
                <input
                  type="text"
                  name="DESCRICAO"
                  id="descricao"
                  defaultValue={stateData.tpo_descricao}
                  autoComplete="given-name"
                  className="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>
            <div>
              <label htmlFor="imposto" className="block text-sm font-semibold leading-6 text-gray-900">
                Valor Imposto
              </label>
              <div className="mt-2.5">
                <input
                  type="text"
                  name="IMPOSTO"
                  id="imposto"
                  defaultValue={stateData.tpo_imposto}
                  autoComplete="family-name"
                  className="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>
            <div className="sm:col-span-2 text-right">
              <button
                type="submit"
                className="inline-flex items-center rounded-md bg-green-700 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-800"
              >
                <CheckIcon className="-ml-0.5 mr-1.5 h-5 w-5 text-white" aria-hidden="true" />
                Salvar
              </button>
            </div>
          </div>
        </form>

      </main>
    </div>
  )
}
