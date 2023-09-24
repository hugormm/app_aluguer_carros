<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- inicio do card de pesquisa -->
        <card-component titulo="Pesquisa de Carros">
          <template v-slot:conteudo>
            <div class="row">
              <div class="col mb-3">
                <input-container-component
                  titulo="ID"
                  id="inputId"
                  id-help="idHelp"
                  texto-ajuda="Opcional. Informe o ID do carro"
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
                  titulo="Matricula do Carro"
                  id="inputMatricula"
                  id-help="matriculaHelp"
                  texto-ajuda="Opcional. Informe a matricula do carro"
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputMatricula"
                    aria-describedby="matriculaHelp"
                    placeholder="Matricula do Carro"
                    v-model="busca.placa"
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

        <!-- inicio do card de listagem de carros -->
        <card-component titulo="Relacao de Carros">
          <template v-slot:conteudo>
            <table-component
              :dados="carros.data"
              :dadosFk="modelos.data"
              :visualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalCarroVizualizar',
              }"
              :actualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalCarroActualizar',
              }"
              :remover="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalCarroRemover',
              }"
              :titulos="{
                id: { titulo: 'ID', tipo: 'texto' },
                modelo_id: { titulo: 'Modelo', tipo: 'fk' },
                placa: { titulo: 'Matricula', tipo: 'texto' },
                disponivel: { titulo: 'Disponibilidade', tipo: 'texto' },
                km: { titulo: 'KM', tipo: 'texto' },
                created_at: { titulo: 'Data de Criacao', tipo: 'data' },
              }"
            ></table-component>
          </template>

          <template v-slot:rodape>
            <div class="row">
              <div class="col-10">
                <pages-component>
                  <li
                    v-for="(l, key) in carros.links"
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
                  data-bs-target="#modalCarro"
                >
                  Adicionar
                </button>
              </div>
            </div>
          </template>
        </card-component>

        <!-- fim do card de listagem de carros -->
      </div>
    </div>

    <!-- Modal de adicionar carro -->
    <modal-component id="modalCarro" titulo="Adicionar Marca">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          :detalhes="transacaoDetalhes"
          titulo="Cadastro relizado com sucesso"
          v-if="transacaoStatus == 'Adicionado'"
        >
        </alert-component>
        <alert-component
          tipo="danger"
          :detalhes="transacaoDetalhes"
          titulo="Erro ao tentar cadastrar o Modelo"
          v-if="transacaoStatus == 'Erro'"
        >
        </alert-component>
      </template>

      <template v-slot:conteudo>
        <div class="form-group">
          <select-container-component
            titulo="Nome do Modelo"
            id="novoModelo"
            id-help="novoModeloHelp"
            texto-ajuda="Informe o nome do Modelo"
          >
            <select
              class="form-select"
              aria-label="Default select example"
              v-model="modeloId"
            >
              <option
                v-for="(modelo, key) in modelos.data"
                :key="key"
                :value="modelo.id"
              >
                {{ modelo.nome }}
              </option>
            </select>
          </select-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Matricula do Carro"
            id="novaMatricula"
            id-help="novaMatriculaHelp"
            texto-ajuda="Informe a Matricula do Carro"
          >
            <input
              type="text"
              class="form-control"
              id="novaMatricula"
              aria-describedby="novaMatriculaHelp"
              placeholder="Matricula do Carro"
              v-model="matricula"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Disponibilidade"
            id="disponibilidade"
            id-help="disponibilidadeHelp"
            texto-ajuda="Disponivel?"
          >
            <input
              type="text"
              class="form-control"
              id="disponibilidade"
              aria-describedby="disponibilidadeHelp"
              placeholder="Disponivel?"
              v-model="disponibilidade"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Km"
            id="km"
            id-help="kmHelp"
            texto-ajuda="Km?"
          >
            <input
              type="text"
              class="form-control"
              id="km"
              aria-describedby="kmHelp"
              placeholder="Km?"
              v-model="km"
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

    <!-- Modal de visualizar carro -->

    <modal-component id="modalCarroVizualizar" titulo="Visualizar Carro">
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
        <input-container-component titulo="Matricula">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.placa"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Disponibilidade">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.disponivel"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="KM">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.km"
            disabled
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

    <!-- Modal de remover carro -->

    <modal-component id="modalCarroRemover" titulo="Remover Carro">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          titulo="Transacao realizada com sucesso"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'sucesso'"
        >
        </alert-component>
        <alert-component
          tipo="danger"
          titulo="Erro na transacao"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'erro'"
        >
        </alert-component>
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
        <input-container-component titulo="Matricula">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.placa"
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

    <!-- Modal de actualizacao de carro -->
    <modal-component id="modalCarroActualizar" titulo="Actualizar Carro">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          titulo="Transacao realizada com sucesso"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'sucesso'"
        >
        </alert-component>
        <alert-component
          tipo="danger"
          titulo="Erro na transacao"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'erro'"
        >
        </alert-component>
      </template>

      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component
            titulo="Matricula do Carro"
            id="actualizarMatricula"
            id-help="actualizarMatriculaHelp"
            texto-ajuda="Informe a matricula do Carro"
          >
            <input
              type="text"
              class="form-control"
              id="actualizarMatricula"
              aria-describedby="actualizarMatriculaHelp"
              placeholder="Matricula do Carro"
              v-model="$store.state.item.placa"
            />
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Km"
            id="actualizarKm"
            id-help="actualizarKmHelp"
            texto-ajuda="Informe os kms do Carro"
          >
            <input
              type="text"
              class="form-control"
              id="actualizarKm"
              aria-describedby="actualizarKmHelp"
              placeholder="Km"
              v-model="$store.state.item.km"
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
export default {
  components: { InputContainer },
  data() {
    return {
      urlBase: "http://127.0.0.1:8000/api/v1/carro",
      urlPaginacao: "",
      urlFiltro: "",
      urlFk: "http://127.0.0.1:8000/api/v1/modelo",
      modeloId: "",
      matricula: "",
      disponibilidade: "",
      km: "",
      transacaoStatus: "",
      transacaoDetalhes: {},
      carros: { data: [] },
      modelos: { data: [] },
      busca: { id: "", nome: "", numero_portas: "" },
    };
  },
  methods: {
    actualizar() {
      let formData = new FormData();
      formData.append("_method", "patch");
      formData.append("placa", this.$store.state.item.placa);
      formData.append("km", this.$store.state.item.km);

      let url = this.urlBase + "/" + this.$store.state.item.id;

      let config = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
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
      formData.append("disponivel", this.disponibilidade);
      formData.append("km", this.km);

      let config = {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
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
      console.log(url);

      axios
        .get(url)
        .then((response) => {
          this.carros = response.data;
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
          this.modelos = response.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
  },
  mounted() {
    this.carregarLista(), this.carregarDadosFk();
  },
};
</script>
