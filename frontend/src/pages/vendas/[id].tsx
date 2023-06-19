import { useEffect, useState } from "react";
import Navbar from "@/components/Navbar";
import { ResponseDataVenda, ResponseDataVendaItens } from "@/utils/interfaces";
import { useRouter } from "next/router";

export default function Page() {
  const [stateData, setStateData] = useState({} as ResponseDataVenda);
  const [stateDataItensVenda, setStateDataItensVenda] = useState([] as ResponseDataVendaItens[]);
  const router = useRouter();

  useEffect(() => {
    async function fetchData() {
      if (!router.query.id)
        return
      try {
        const res = await fetch(`http://localhost:8080/api/vendas/${router.query.id}`).then(r => r.json() as Promise<ResponseDataVenda>)
        setStateData(res);

        const resItens = await fetch(`http://localhost:8080/api/vendas/${router.query.id}/itens`).then(r => r.json() as Promise<ResponseDataVendaItens[]>)
        setStateDataItensVenda(resItens);
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
              Venda {stateData.ven_id ?? '...'}
            </h2>
          </div>
        </div>
      </div>
      <main>

        <form action="#" method="POST" className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
            <div className="col-span-2">
              <label htmlFor="nome" className="block text-sm font-semibold leading-6 text-gray-900">
                Cliente
              </label>
              <div className="mt-2.5">
                <input
                  type="text"
                  name="nome"
                  id="nome"
                  defaultValue={stateData.ven_cliente}
                  autoComplete="given-name"
                  className="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>
            <table className="divide-y divide-gray-200">
              <thead>
                <tr>
                  <th className="text-left">Des.</th>
                  <th className="text-right">Qtd.</th>
                  <th className="text-right">Un. R$.</th>
                  <th className="text-right">Imp. Total R$</th>
                  <th className="text-right">Total R$</th>
                </tr>
              </thead>
              {stateDataItensVenda?.map((item, key) => {
                return (
                  <tr key={key} >
                    <td className="text-base font-medium text-gray-900">
                      {item.ven_ite_descricao}
                    </td>
                    <td className="text-right">
                      {item.ven_ite_qtde}
                    </td>
                    <td className="text-right">
                      {(item.ven_ite_valor * 1).toFixed(2)}
                    </td>
                    <td className="text-right">
                      {(item.ven_ite_qtde * ((item.ven_ite_imposto / 100) * item.ven_ite_valor)).toFixed(2)}
                    </td>
                    <td className="text-right">
                      {(item.ven_ite_qtde * item.ven_ite_valor).toFixed(2)}
                    </td>
                  </tr>
                )
              })}
            </table>
            <div className="text-right">
              <h2 className="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Total Impostos: {(stateData.ven_valor_total_impostos * 1).toFixed(2)}
              </h2>
              <h2 className="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Valor Total: {(stateData.ven_valor_total * 1).toFixed(2)}
              </h2>
            </div>
          </div>
        </form>

      </main>
    </div>
  )
}
