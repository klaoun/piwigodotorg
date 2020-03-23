  <section class="container-fluide container-fluide-changelogs">
    <div class="container">
      <div class="row changelogs-intro">
        <div class="col-md-6 changelogs-intro-text">
          <h1>{'Changelogs'|translate}</h1>
          <p>{'porg_changelogs_desc1'|translate} {'porg_changelogs_desc2'|translate}</p>
        </div>
        <div class="col-md-6 text-center changelogs-intro-image">
         <img src="{$PORG_ROOT_URL}images/changelogs/changelogs-illustration.svg"/>
        </div>
      </div>
    </div>
  </section>

  <div class="container container-changelogs-versions">
    <div class="row grid text-center">

      {foreach from=$releases key=version item=summary}

      <!--<div class="version-box">-->

        <div class="version-{$releases[$version].state}">
          
          {if {$releases[$version].state} == 'major'}
          <h2>Piwigo {$version}</h2>
          <p>{$releases[$version].released_on}</p>
          <div class="version-major-content">
            <ul class="bold">
            {foreach from=$releases[$version].summary key=key item=summary}
              <li>{$summary|translate}</li>
            {/foreach}
            </ul>
          </div>
          {/if}

          {if {$releases[$version].state} == 'minor'}
          <h2>{$version}</h2>
          <p>{$releases[$version].released_on}</p>
          {/if}

          <div class="read-more">
            <a href="{$PORG_ROOT}{$URL.release}-{$version}">{'Read more'|translate}</a>
          </div>
        </div>
     <!-- </div>-->

      {/foreach}

       <div class="col-md-12 col-xs-12 version-box">
        <div class="primary-version">
          <div class="col-xs-12">
            <h2>Piwigo 1.0.0</h2>
            <p>2002-04-15</p>
          </div>
          <div class="col-xs-12 primary-version-content">
            <p>Birth of Piwigo</p>
          </div>
          <div class="col-xs-12 primary-version-read-more">
            <p><a href="{$PORG_ROOT}{$URL.release}-1.0.0">Read more</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="container-fluide container-fluide-techs-view">
    <div class="container">
      <div class="row text-center">
        <p>{'porg_changelogs_technicals_desc1'|translate:'http://piwigo.org/doc/doku.php?id=about:release_and_branchs'}</p>
      </div>
    </div>
  </section>

  <script src="{$PORG_ROOT_URL}js/changelogs.js"></script>