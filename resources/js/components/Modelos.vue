<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- inicio do card de pesquisa -->
        <card-component titulo="Pesquisa de Modelos">
          <template v-slot:conteudo>
            <div class="row">
              <div class="col mb-3">
                <input-container-component
                  titulo="ID"
                  id="inputId"
                  id-help="idHelp"
                  texto-ajuda="Opcional. Informe o ID do modelo"
                >
                  <input
                    type="number"
                    class="form-control"
                    id="inputId"
                    aria-describedby="idHelp"
                    placeholder="ID"
                    v-model="busca.id"
                  />
                </input-container-component>
              </div>
              <div class="col mb-3">
                <input-container-component
                  titulo="Nome do Modelo"
                  id="inputNome"
                  id-help="nomeHelp"
                  texto-ajuda="Opcional. Informe o nome do modelo"
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputNome"
                    aria-describedby="nomeHelp"
                    placeholder="Nome do Modelo"
                    v-model="busca.nome"
                  />
                </input-container-component>
              </div>
              <div class="col mb-3">
                <input-container-component
                  titulo="Numero de Portas"
                  id="inputPortas"
                  id-help="portasHelp"
                  texto-ajuda="Opcional. Informe o numero de portas"
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputPortas"
                    aria-describedby="portasHelp"
                    placeholder="Numero de Portas"
                    v-model="busca.numero_portas"
                  />
                </input-container-component>
              </div>
            </div>
          </template>

          <template v-slot:rodape>
            <button
              type="submit"
              class="btn btn-primary btn-sm float-end"
              @click="pesquisar()"
            >
              Pesquisar
            </button>
          </template>
        </card-component>

        <!-- fim do card de pesquisa -->

        <!-- inicio do card de listagem de modelos -->
        <card-component titulo="Relacao de Modelos">
          <template v-slot:conteudo>
            <table-component
              :dados="modelos.data"
              :dadosFk="marcas.data"
              :visualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalModeloVizualizar',
              }"
              :actualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalModeloActualizar',
              }"
              :remover="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalModeloRemover',
              }"
              :titulos="{
                id: { titulo: 'ID', tipo: 'texto' },
                marca_id: { titulo: 'Marca', tipo: 'fk' },
                nome: { titulo: 'Nome', tipo: 'texto' },
                numero_portas: { titulo: 'Nº Portas', tipo: 'texto' },
                lugares: { titulo: 'Lugares', tipo: 'texto' },
                air_bag: { titulo: 'Air Bag', tipo: 'texto' },
                abs: { titulo: 'Abs', tipo: 'texto' },
                imagem: { titulo: 'Imagem', tipo: 'imagem' },
                created_at: { titulo: 'Data de Criacao', tipo: 'data' },
              }"
            ></table-component>
          </template>

          <template v-slot:rodape>
            <div class="row">
              <div class="col-10">
                <pages-component>
                  <li
                    v-for="(l, key) in modelos.links"
                    :key="key"
                    :class="l.active ? 'page-item active' : 'page-item'"
                    @click="paginacao(l)"
                  >
                    <a class="page-link" v-html="l.label"></a>
                  </li>
                </pages-component>
              </div>
              <div class="col">
                <!-- Button trigger modal -->
                <button
                  type="button"
                  class="btn btn-primary btn-sm float-end"
                  data-bs-toggle="modal"
                  data-bs-target="#modalModelo"
                >
                  Adicionar
                </button>
              </div>
            </div>
          </template>
        </card-component>

        <!-- fim do card de listagem de modelos -->
      </div>
    </div>

    <!-- Modal de adicionar modelo -->
    <modal-component id="modalModelo" titulo="Adicionar Modelo">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          :detalhes="transacaoDetalhes"
          titulo="Cadastro relizado com sucesso"
          v-if="transacaoStatus == 'Adicionado'"
        ></alert-component>
        <alert-component
          tipo="danger"
          :detalhes="transacaoDetalhes"
          titulo="Erro ao tentar cadastrar o Modelo"
          v-if="transacaoStatus == 'Erro'"
        ></alert-component>
      </template>

      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component
            titulo="Nome do Modelo"
            id="novoNome"
            id-help="novoNomeHelp"
            texto-ajuda="Informe o nome do Modelo"
          >
            <input
              type="text"
              class="form-control"
              id="novoNome"
              aria-describedby="novoNomeHelp"
              placeholder="Nome do Modelo"
              v-model="nomeModelo"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <select-container-component
            titulo="Nome da Marca"
            id="novoMarca"
            id-help="novoMarcaHelp"
            texto-ajuda="Informe o nome da Marca"
          >
            <select
              class="form-select"
              aria-label="Default select example"
              v-model="marcaId"
            >
              <option
                v-for="(marca, key) in marcas.data"
                :key="key"
                :value="marca.id"
              >
                {{ marca.nome }}
              </option>
            </select>
          </select-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Imagem"
            id="novaImagem"
            id-help="novaImagemHelp"
            texto-ajuda="Selecione uma imagem"
          >
            <input
              type="file"
              class="form-control"
              id="novaImagem"
              aria-describedby="novaImagemHelp"
              placeholder="Selecione uma imagem"
              @change="carregarImagem($event)"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Numero de Portas"
            id="numeroPortas"
            id-help="numeroPortasHelp"
            texto-ajuda="Informe o numero de Portas"
          >
            <input
              type="text"
              class="form-control"
              id="numeroPortas"
              aria-describedby="numeroPortasHelp"
              placeholder="Numero de Portas"
              v-model="numeroPortas"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Lugares"
            id="lugares"
            id-help="lugaresHelp"
            texto-ajuda="Informe o numero de lugares"
          >
            <input
              type="text"
              class="form-control"
              id="lugares"
              aria-describedby="lugaresHelp"
              placeholder="Numero de lugares"
              v-model="lugares"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Air Bag"
            id="airBag"
            id-help="airBagHelp"
            texto-ajuda="Informe se tem Air Bag"
          >
            <input
              type="text"
              class="form-control"
              id="airBag"
              aria-describedby="airBagHelp"
              placeholder="Air Bag"
              v-model="airBag"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Abs"
            id="abs"
            id-help="absHelp"
            texto-ajuda="Informe se tem Abs"
          >
            <input
              type="text"
              class="form-control"
              id="abs"
              aria-describedby="absHelp"
              placeholder="Abs"
              v-model="abs"
            />
          </input-container-component>
        </div>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="salvar()">
          Salvar
        </button>
      </template>
    </modal-component>

    <!-- Modal de visualizar modelo -->

    <modal-component id="modalModeloVizualizar" titulo="Visualizar Modelo">
      <template v-slot:alertas></template>
      <template v-slot:conteudo>
        <input-container-component titulo="ID">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.id"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Nome do Modelo">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Numero de Portas">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.numero_portas"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Lugares">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.lugares"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Air Bag">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.air_bag"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Abs">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.abs"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Imagem">
          <img
            :src="'storage/' + $store.state.item.imagem"
            v-if="$store.state.item.imagem"
          />
        </input-container-component>
        <input-container-component titulo="Data de criacao">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.created_at"
            disabled
          />
        </input-container-component>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
      </template>
    </modal-component>

    <!-- Modal de remover modelo -->

    <modal-component id="modalModeloRemover" titulo="Remover Modelo">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          titulo="Transacao realizada com sucesso"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'sucesso'"
        ></alert-component>
        <alert-component
          tipo="danger"
          titulo="Erro na transacao"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'erro'"
        ></alert-component>
      </template>
      <template
        v-slot:conteudo
        v-if="$store.state.transacao.status != 'sucesso'"
      >
        <input-container-component titulo="ID">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.id"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Nome do Modelo">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-container-component>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button
          type="button"
          class="btn btn-danger"
          @click="remover()"
          v-if="$store.state.transacao.status != 'sucesso'"
        >
          Remover
        </button>
      </template>
    </modal-component>

    <!-- Modal de actualizacao de modelo -->
    <modal-component id="modalModeloActualizar" titulo="Actualizar Modelo">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          titulo="Transacao realizada com sucesso"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'sucesso'"
        ></alert-component>
        <alert-component
          tipo="danger"
          titulo="Erro na transacao"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'erro'"
        ></alert-component>
      </template>

      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component
            titulo="Nome do Modelo"
            id="actualizarNome"
            id-help="actualizarNomeHelp"
            texto-ajuda="Informe o nome do Modelo"
          >
            <input
              type="text"
              class="form-control"
              id="actualizarNome"
              aria-describedby="actualizarNomeHelp"
              placeholder="Nome do Modelo"
              v-model="$store.state.item.nome"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Numero de Portas"
            id="actualizarNumeroPortas"
            id-help="actualizarNumeroPortasHelp"
            texto-ajuda="Informe o numero de portas"
          >
            <input
              type="text"
              class="form-control"
              id="actualizarNumeroPortas"
              aria-describedby="actualizarNumeroPortasHelp"
              placeholder="Numero de Portas"
              v-model="$store.state.item.numero_portas"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Numero de Lugares"
            id="actualizarNumeroLugares"
            id-help="actualizarNumeroLugaresHelp"
            texto-ajuda="Informe o numero de lugares"
          >
            <input
              type="text"
              class="form-control"
              id="actualizarNumeroLugares"
              aria-describedby="actualizarNumeroLugaresHelp"
              placeholder="Numero de Lugares"
              v-model="$store.state.item.lugares"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="AirBag"
            id="actualizarAirBag"
            id-help="actualizarAirBagHelp"
            texto-ajuda="Informe se possui Air Bag"
          >
            <input
              type="text"
              class="form-control"
              id="actualizarAirBag"
              aria-describedby="actualizarAirBagHelp"
              placeholder="Air Bag"
              v-model="$store.state.item.airBag"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Abs"
            id="actualizarAbs"
            id-help="actualizarAbsHelp"
            texto-ajuda="Informe se possui Abs"
          >
            <input
              type="text"
              class="form-control"
              id="actualizarAbs"
              aria-describedby="actualizarAbsHelp"
              placeholder="Abs"
              v-model="$store.state.item.abs"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Imagem"
            id="actualizarImagem"
            id-help="actualizarImagemHelp"
            texto-ajuda="Selecione uma imagem"
          >
            <input
              type="file"
              class="form-control"
              id="actualizarImagem"
              aria-describedby="actualizarImagemHelp"
              placeholder="Selecione uma imagem"
              @change="carregarImagem($event)"
            />
          </input-container-component>
        </div>
      </template>
      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="actualizar()">
          Actualizar
        </button>
      </template>
    </modal-component>
  </div>
</template>

<script>
import InputContainer from "./InputContainer.vue";
import SelectContainer from "./SelectContainer.vue";
export default {
  components: {
    InputContainer,
    SelectContainer,
  },
  data() {
    return {
      urlBase: "http://127.0.0.1:8000/api/v1/modelo",
      urlPaginacao: "",
      urlFk: "http://127.0.0.1:8000/api/v1/marca",
      urlFiltro: "",
      nomeModelo: "",
      marcaId: "",
      arquivoImagem: [],
      numeroPortas: "",
      lugares: "",
      airBag: "",
      abs: "",
      transacaoStatus: "",
      transacaoDetalhes: {},
      modelos: { data: [] },
      marcas: { data: [] },
      busca: { id: "", nome: "", numero_portas: "" },
    };
  },
  methods: {
    actualizar() {
      let formData = new FormData();
      formData.append("_method", "patch");
      formData.append("nome", this.$store.state.item.nome);
      formData.append("numero_portas", this.$store.state.item.numero_portas);
      formData.append("lugares", this.$store.state.item.lugares);
      formData.append("air_bag", this.$store.state.item.air_bag);
      formData.append("abs", this.$store.state.item.abs);
      if (this.arquivoImagem[0]) {
        formData.append("imagem", this.arquivoImagem[0]);
      }

      let url = this.urlBase + "/" + this.$store.state.item.id;

      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      axios
        .post(url, formData, config)
        .then((response) => {
          actualizarImagem.value = "";
          this.$store.state.transacao.status = "sucesso";
          this.$store.state.transacao.mensagem =
            "Registo actualizado com sucesso";
          this.carregarLista();
        })
        .catch((errors) => {
          this.$store.state.transacao.status = "erro";
          this.$store.state.transacao.mensagem = errors.response.data.message;
          this.$store.state.transacao.dados = errors.response.data.errors;
        });
    },
    remover() {
      let confirmacao = confirm(
        "Tem a certeza que deseja remover esse registo?"
      );

      if (!confirmacao) {
        return false;
      }

      let url = this.urlBase + "/" + this.$store.state.item.id;

      let formData = new FormData();
      formData.append("_method", "delete");

      axios
        .post(url, formData)
        .then((response) => {
          this.$store.state.transacao.status = "sucesso";
          this.$store.state.transacao.mensagem = response.data.msg;
          this.carregarLista();
        })
        .catch((errors) => {
          this.$store.state.transacao.status = "erro";
          this.$store.state.transacao.mensagem = errors.response.data.erro;
        });
    },
    pesquisar() {
      let filtro = "";

      for (let chave in this.busca) {
        if (this.busca[chave]) {
          if (filtro != "") {
            filtro += ";";
          }
          filtro += chave + ":like:" + this.busca[chave];
        }
      }

      if (filtro != "") {
        this.urlPaginacao = "page=1";
        this.urlFiltro = "&filtro=" + filtro;
      } else {
        this.urlFiltro = "";
      }

      this.carregarLista();
    },
    paginacao(l) {
      if (l.url) {
        this.urlPaginacao = l.url.split("?")[1];
        this.carregarLista();
      }
    },
    carregarImagem(e) {
      this.arquivoImagem = e.target.files;
    },
    salvar() {
      let formData = new FormData();
      formData.append("nome", this.nomeModelo);
      formData.append("marca_id", this.marcaId);
      formData.append("imagem", this.arquivoImagem[0]);
      formData.append("numero_portas", this.numeroPortas);
      formData.append("lugares", this.lugares);
      formData.append("air_bag", this.airBag);
      formData.append("abs", this.abs);
      console.log(this.marcaId);

      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      axios
        .post(this.urlBase, formData, config)
        .then((response) => {
          this.transacaoStatus = "Adicionado";
          this.transacaoDetalhes = {
            mensagem: "Id do registo : " + response.data.id,
          };
          novoNome.value = "";
          novaImagem.value = "";
        })
        .catch((errors) => {
          this.transacaoStatus = "Erro";
          this.transacaoDetalhes = {
            mensagem: errors.response.data.message,
            dados: errors.response.data.errors,
          };
        });
    },
    carregarLista() {
      let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro;

      axios
        .get(url)
        .then((response) => {
          this.modelos = response.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    carregarDadosFk() {
      let url = this.urlFk;

      axios
        .get(url)
        .then((response) => {
          this.marcas = response.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
  },
  mounted() {
    this.carregarLista();
    this.carregarDadosFk();
  },
};
</script>
