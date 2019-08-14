<template>
  <div id="repositories">
    <b-modal
      id="repository-modal"
      ref="modal"
      title="Repository anlegen"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <b-form-group
          label="URL"
          label-for="repository_form-url"
          invalid-feedback="URL is required"
        >
          <b-form-input id="repository_form-url" v-model="repository_form.url" required></b-form-input>
        </b-form-group>
        <b-form-group
          label="Type"
          label-for="repository_form-type"
          invalid-feedback="Type is required"
        >
          <b-form-select
            id="repository_form-type"
            v-model="repository_form.type"
            :options="repository_types"
            required
          ></b-form-select>
        </b-form-group>
      </form>
    </b-modal>

    <h2>Repositories</h2>

    <div class="repositories" v-for="(repository) in repositories" :key="repository.url">
      <h4>{{repository.url}}</h4>
      <div>
        Type: {{repository.type}}
        <button v-b-modal.repository-modal class="btn btn-info">Edit</button>
        <button @click="removeRepository(repository)" class="btn btn-error">Delete</button>
      </div>
      
    </div>

    <b-button class="btn btn-info mt-4" v-b-modal.repository-modal>Repository hinzufügen</b-button>
  </div>
</template>

<style>
.repositories {
  background: #f9f9f9;
  padding: 10px 20px;
}

.repositories + .repositories {
  margin-top: 20px;
}
</style>

<script>
import { authHeader } from "../../_helpers";
export default {
  data() {
    return {
      // repositories: {},
      repository_form: {},
      repository_types: [
        { value: "composer", text: "Composer" },
        { value: "vcs", text: "Git (VCS)" },
        { value: "path", text: "Lokaler Pfad" }
      ]
    };
  },
  methods: {
    logout() {
      localStorage.removeItem("user");
    },
    fetchRepositories() {
      const requestOptions = {
        method: "GET",
        headers: authHeader()
      };

      var that = this;

      this.$http
        .get(
          (location.href.indexOf("oxid.phar.php") !== -1
            ? "/oxid.phar.php"
            : "") + "/oxid/moduleinstaller/repositories/",
          requestOptions
        )
        .then(response => {
          this.repositories = response.body.repositories;
        })
        .catch(() => {
          that.logout();
          location.reload(true);
        });
    },
    checkFormValidity() {
        const valid = this.$refs.form.checkValidity();
        this.nameState = valid ? "valid" : "invalid";
        return valid;
    },
    resetModal() {
        this.repository_form = {};
    },
    handleOk(bvModalEvt) {
        bvModalEvt.preventDefault();
        this.handleSubmit();
    },
    removeRepository(repository) {
        const requestOptions = {
            method: "DELETE",
            headers: authHeader()
        };

        var that = this;

        this.$http
            .delete(
                (location.href.indexOf("oxid.phar.php") !== -1
                ? "/oxid.phar.php"
                : "") + "/oxid/moduleinstaller/repositories/",
                {body: {repository: repository}, headers: authHeader()},
                requestOptions
            )
            .then(response => {
            })
            .catch(() => {
                that.logout();
                location.reload(true);
            });
    },
    handleSubmit() {
        if (!this.checkFormValidity()) {
            return;
        }
        this.repositories.push({
            type: this.repository_form.type,
            url: this.repository_form.url
        });
        this.$nextTick(() => {
            this.$refs.modal.hide();
            
            const requestOptions = {
                method: "POST",
                headers: authHeader()
            };

            var that = this;

            this.$http
                .post(
                    (location.href.indexOf("oxid.phar.php") !== -1
                    ? "/oxid.phar.php"
                    : "") + "/oxid/moduleinstaller/repositories/",
                    {repository: this.repository_form},
                    requestOptions
                )
                .then(response => {
                    console.log(response)
                })
                .catch(() => {
                    that.logout();
                    location.reload(true);
                });
        });
    }
  },
  computed: {
    repositories () {
      return this.$store.state.repositories.all;
    }
  },
  created() {
    // this.fetchRepositories();
    this.$store.dispatch('repositories/getAll');
  }
};
</script>