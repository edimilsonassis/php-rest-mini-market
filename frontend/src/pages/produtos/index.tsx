import Link from "next/link";
import { useEffect, useState } from "react";
import { PlusIcon } from "@heroicons/react/24/outline";
import { useRouter } from "next/router";
import Navbar from "@/components/Navbar";
import { ResponseDataProduto } from "@/utils/interfaces";

export default function Page() {
  const [stateData, setStateData] = useState([] as ResponseDataProduto[]);
  const router = useRouter();

  async function cadastrarNovo() {
    const res = await fetch(`http://localhost:8080/api/produtos/`, { method: 'POST' }).then(r => r.json())

    router.push(`/produtos/${res}`)
  }

  useEffect(() => {
    async function fetchData() {
      try {
        const res = await fetch(`http://localhost:8080/api/produtos/`).then(r => r.json() as Promise<ResponseDataProduto[]>)
        setStateData(res);
      } catch (err) {
        console.error(err);
      }
    }
    fetchData();
  }, []);

  return (
    <div className="min-h-full">
      <Navbar />
      <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div className="lg:flex lg:items-center lg:justify-between">
          <div className="min-w-0 flex-1">
            <h2 className="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
              Produtos
            </h2>
          </div>
          <div className="mt-5 flex lg:ml-4 lg:mt-0">
            <span className="hidden sm:block">
              <button
                type="button"
                onClick={() => cadastrarNovo()}
                className="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
              >
                <PlusIcon className="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                Novo
              </button>
            </span>
          </div>
        </div>
      </div>
      <main>
        <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <ul role="list" className="divide-y divide-gray-200">
            {stateData?.map((item, key) => {
              return (
                <li key={key} className="flex py-6">
                  <div className="flex flex-1 flex-col">
                    <Link href={`/produtos/${item.prd_id}`}>
                      <div>
                        <div className="flex justify-between text-base font-medium text-gray-900">
                          <h3>
                            {item.prd_nome}
                          </h3>
                          <p className="ml-4">{item.prd_valor}</p>
                        </div>
                        <p className="mt-1 text-sm text-gray-500">{item.tpo_descricao}</p>
                      </div>
                    </Link>
                  </div>
                </li>
              )
            })}
          </ul>
        </div>
      </main>
    </div>
  )
}
