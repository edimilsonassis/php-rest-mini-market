
export interface ResponseDataProduto {
    prd_id: number;
    prd_id_tipo: number;
    prd_nome: string;
    prd_valor: string;
    tpo_descricao: string;
    tpo_imposto: string;
}

export interface ResponseDataVenda {
    ven_id: number;
    ven_cliente: string;
    ven_valor_total: number;
    ven_valor_total_impostos: number;
    ven_data: string;
}

export interface ResponseDataVendaItens {
    ven_ite_id: number,
    ven_ite_id_produto: number,
    ven_ite_descricao: string,
    ven_ite_valor: number,
    ven_ite_qtde: number,
    ven_ite_imposto: number
}

export interface ResponseDataTipos {
    tpo_id: number;
    tpo_descricao: string;
    tpo_imposto: number;
}
