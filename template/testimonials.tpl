  <section class="container-fluide pwg-testimonies">
    <div class="container">
      <div class="equal">
        <div class="col-md-6 testimonials-title">
          <h1>{'Testimonials'|translate}</h1>
          <p>{'porg_testimonials_desc1'|translate} {'porg_testimonials_desc2'|translate}</p>
        </div>
        <div class="col-sm-6 first-image">
          <img src="{$PORG_ROOT_URL}images/testimonies/testimonials-first-image.svg">
        </div>
      </div>
    </div>
  </section>

 <section class="container">

     <div class="row">

       <div class="card-columns" >
         {foreach from=$testimonials key=testimonials_date item=testimonials_content}

         <div class="card pwg-testimonies-advice {$testimonials_content.user.type}">
           <div class="card-body">
             <div class="">
               <p>{$testimonials_content.content}</p>
               <div class=" pwg-testimonies-user">
                 <div class="pwg-testimonies-name">
                   <p><span class="bold">{$testimonials_content.user.username}</span><br>{if isset($testimonials_content.user.organisation)}{$testimonials_content.user.organisation}{else}{$testimonials_content.user.type}{/if}</p>
                   <p>{$testimonials_content.user.country}, {$testimonials_content.added_on}</p>
                 </div>
               </div>
             </div>
           </div>
         </div>
         {/foreach}
       </div>

     </div>


</section>





{assign var=share_url value="{$URL.contact}&type=testimonial"}

  <section class="container-fluide share-testimonies">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <p>{'porg_testimonials_share_title'|translate}<br>
          {'porg_testimonials_share_desc1'|translate:$share_url}</p>
        </div>
      </div>
    </div>
  </section>
