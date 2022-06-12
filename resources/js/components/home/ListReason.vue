<template>
  <div id="reasons">
      <h2 class="reason-title">Why book with VTravel?</h2>
      <a-row type="flex" justify="center" align="top">
        <a-col :xs="12" :sm="12" :md="6" :lg="6">
          <div class="reason-item">
            <img src="/img/reason1.svg" alt="reason">
            <h4>Ultimate flexibility</h4>
            <p>You’re in control, with free cancellation and payment options to satisfy any plan or budget.</p>
          </div>
        </a-col>
        <a-col :xs="12" :sm="12" :md="6" :lg="6">
          <div class="reason-item">
            <img src="/img/reason2.svg" alt="reason">
            <h4>Memorable experiences</h4>
            <p>Browse and book tours and activities so incredible, you’ll want to tell your friends.</p>
          </div>
        </a-col>
        <a-col :xs="12" :sm="12" :md="6" :lg="6">
          <div class="reason-item">
            <img src="/img/reason3.svg" alt="reason">
            <h4>Quality at our core</h4>
            <p>High quality standards. Millions of reviews. A Tripadvisor company.</p>
          </div>
        </a-col>
        <a-col :xs="12" :sm="12" :md="6" :lg="6">
          <div class="reason-item" style="margin-right: 0;">
            <img src="/img/reason4.svg" alt="reason">
            <h4>Award-winning support</h4>
            <p>New price? New plan? No problem. We’re here to help, 24/7.</p>
          </div>
        </a-col>
      </a-row>
  </div>
</template>

<script>
</script>

<style scoped>
  #reasons {
    padding: 40px 80px;
    text-align: center;
  }
  #reasons .reason-title {
    font-size: 28px;
    margin-bottom: 80px;
  }
  .reason-item {
    margin-right: 80px;
  }
  h4 {
    font-size: 20px;
    margin: 20px 0;
  }
  p {
    font-size: 18px;
    text-align: justify;
  }
</style>
