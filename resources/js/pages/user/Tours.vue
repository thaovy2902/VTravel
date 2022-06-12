<template>
  <div>
    <loading-full-screen v-if="loading" />

    <section class="tours-banner">
      <img src="/img/tours-banner.jpg" alt="tours-banner" />
      <span>VTravel</span>
    </section>

    <section style="margin-top:16px">
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="5" :lg="5">
          <card-filter-tour
            @changeCategory="changeCategory"
            @changeDepart="changeDepart"
            @changeToPlace="changeToPlace"
            @changeFeatured="changeFeatured"
            @changePrice="changePrice"
            @clearFilter="clearFilter"
          />
        </a-col>
        <a-col :xs="24" :sm="24" :md="19" :lg="19">
          <nav-search-tour @onSearch="onSearch" @changeSort="changeSort" />

          <list-tour :tours="tours" :pagination="pagination" @changePage="changePage" />
        </a-col>
      </a-row>
    </section>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  import CardFilterTour from "@/components/tour/CardFilterTour";
  import NavSearchTour from "@/components/tour/NavSearchTour";
  import ListTour from "@/components/tour/ListTour";
  export default {
    components: { CardFilterTour, NavSearchTour, ListTour },
    metaInfo: {
      title: "Tours",
      meta: [{ vmid: "description", name: "description", content: "VTravel - Tours" }],
    },
    data() {
      return {
        pagination: {
          total: 0,
          current_page: 1,
          per_page: 9,
        },
        query: {
          page: 1,
          sortBy: "",
          orderBy: "",
          q: "",
          category: 0,
          depart: "",
          toPlace: "",
          featured: "",
          minPrice: "",
          maxPrice: "",
        },
      };
    },
    computed: {
      ...mapGetters("searchToursAdvance", ["tours", "loading"]),
    },
    created() {
      this.fetchData();
    },
    watch: {
      $route: "fetchData",
    },
    methods: {
      ...mapActions("searchToursAdvance", ["fetchTours"]),
      async fetchData() {
        const pagination = { ...this.pagination };
        const { total, current_page, per_page } = await this.fetchTours();
        pagination.total = total;
        pagination.current_page = current_page;
        pagination.per_page = per_page;
        this.pagination = pagination;
      },
      //Filter
      changeCategory(value) {
        this.query.category = value;
        this.query.toPlace = "";
        this.hanldeChangeRoute();
      },
      changeDepart(value) {
        this.query.depart = value;
        this.hanldeChangeRoute();
      },
      changeToPlace(value) {
        this.query.toPlace = value;
        this.hanldeChangeRoute();
      },
      changeFeatured(value) {
        this.query.featured = value;
        this.hanldeChangeRoute();
      },
      changePrice(min, max) {
        this.query.minPrice = min;
        this.query.maxPrice = max;
        this.hanldeChangeRoute();
      },
      //Nav search
      changePage(page) {
        this.query.page = page;
        this.hanldeChangeRoute();
      },
      onSearch(value) {
        this.query.q = value;
        this.hanldeChangeRoute();
      },
      changeSort({ sortBy, orderBy }) {
        this.query.sortBy = sortBy;
        this.query.orderBy = orderBy;
        this.hanldeChangeRoute();
      },
      clearFilter() {
        const { query } = this;
        query.page = 1;
        query.sortBy = "";
        query.orderBy = "";
        query.q = "";
        query.category = 0;
        query.depart = "";
        query.toPlace = "";
        query.featured = "";
        query.minPrice = "";
        query.maxPrice = "";
        this.hanldeChangeRoute();
      },
      hanldeChangeRoute() {
        const query = { ...this.query };
        let sendQuery = {};
        sendQuery.page = query.page;
        if (query.sortBy) sendQuery.sortBy = query.sortBy;
        if (query.orderBy) sendQuery.orderBy = query.orderBy;
        if (query.q) sendQuery.q = query.q;

        if (query.category) sendQuery.category = query.category;
        if (query.depart) sendQuery.depart = query.depart;
        if (query.toPlace) sendQuery.toPlace = query.toPlace;
        if (query.minPrice) sendQuery.minPrice = query.minPrice;
        if (query.maxPrice) sendQuery.maxPrice = query.maxPrice;
        if (query.featured) sendQuery.featured = query.featured;

        this.$router.push({ query: { ...sendQuery } });
        this.query = sendQuery;
      },
      targetAffix() {
        return document.getElementById("app");
      },
    },
  };
</script>

<style lang="less" scoped>
  .tours-banner {
    position: relative;
    margin: 0 -80px;
    img {
      width: 100%;
      max-height: 320px;
    }
    span {
      position: absolute;
      color: #fff;
      bottom: 0;
      left: 0;
      height: 86px;
      width: 100%;
      background: rgba(0, 0, 0, 0.25);
      font-size: 32px;
      text-transform: uppercase;
      font-weight: 600;
      font-family: cursive;
      line-height: 32px;
      padding: 27px 80px;
    }
  }
</style>
