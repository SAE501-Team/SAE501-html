{extends file='page.tpl'} {block name='page_content_container'}
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
<link href="{$urls.child_css_url}/custom-acc.css" rel="stylesheet">

<section id="content" class="page-home p-0 m-0">
  <canvas class="webgl chevreAnim"></canvas>
  <script type="importmap">
    {
      "imports": {
        "three": "https://cdn.jsdelivr.net/npm/three@0.155.0/build/three.module.js",
        "GLTFLoader": "https://cdn.jsdelivr.net/npm/three@0.155.0/examples/jsm/loaders/GLTFLoader.js",
        "SkeletonUtils": "https://cdn.jsdelivr.net/npm/three@0.155.0/examples/jsm/utils/SkeletonUtils.js"
      }
    }
  </script>
  <script>
    const modelUrl = "{$urls.child_theme_assets}models/chevre/behhmodel_rig.gltf";
    //const audioUrl = "{$theme_dir}assets/sound/clap_fesses.mp3";
  </script>
  <script type="module" src="{$urls.child_js_url}custom_3d.js"></script>

  <!-- Section Hero avec Lettres Flottantes -->
  <div
    class="relative w-full m-0 h-screen overflow-hidden p-0 bg-gradient-to-t from-blue-500 via-purple-500 to-pink-500"
  >
    <canvas id="milk_bg"></canvas>

    <!-- Lettres flottantes -->
    <div
      class="absolute inset-0 top-0 flex justify-center items-center z-10"
      id="floating-letters"
    >
      <span
        class="letter text-white text-[12rem] font-bold transform text-shadow absolute"
        id="letter-b"
        style="left: 20%; bottom: 20%"
        >B</span
      >
      <span
        class="letter text-white text-[12rem] font-bold transform text-shadow absolute"
        id="letter-e"
        style="left: 30%; bottom: 10%"
        >E</span
      >
      <span
        class="letter text-white text-[12rem] font-bold transform text-shadow absolute"
        id="letter-h"
        style="right: 20%; top: 10%"
        >H</span
      >
      <span
        class="letter text-white text-[12rem] font-bold transform text-shadow absolute"
        id="letter-h-2"
        style="right: 30%; top: 20%"
        >H</span
      >
    </div>
  </div>
  <!-- Section Méeelange Magique -->
  <div
    class="flex flex-col lg:flex-row items-center lg:justify-between w-full mx-auto px-4 py-8 gap-8 relative z-10"
    data-aos="fade-up"
    data-aos-duration="800"
  >
    <img
      src="{$urls.child_img_url}boules-cannelle.jpg"
      alt="boite"
      class="w-full max-w-[200px] sm:max-w-[250px] md:max-w-[300px] lg:max-w-[350px] xl:max-w-[400px] h-auto object-contain"
      data-aos="zoom-in"
      data-aos-delay="200"
    />

    <div
      class="flex flex-col items-start gap-8"
      data-aos="fade-left"
      data-aos-delay="300"
    >
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-pretty">
        Méeelange Magique
      </h1>

      <div
        class="flex flex-col lg:flex-row lg:items-center lg:justify-start gap-4 w-full"
        data-aos="fade-up"
        data-aos-delay="300"
      >
        <p class="text-sm md:text-base lg:text-lg max-w-lg">
          Composez votre mélange idéal avec vos ingrédients favoris. Un mix unique pour des matins gourmands et sur-mesure.        </p>
        <a
          class="bg-blue-500 text-white px-4 py-2 rounded text-left lg:text-left w-fit lg:w-48 hover:bg-blue-600"
          data-aos="flip-left"
          data-aos-delay="400"
          href="{$urls.base_url}fr/content/6-melange-magique"
        >
          Créer mes céréales
      </a>
      </div>

      <div
        class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 w-full"
        data-aos="fade-up"
        data-aos-delay="400"
      >
        <p class="text-sm md:text-base lg:text-lg max-w-lg">
          Explorez les créations originales des autres utilisateurs et laissez-vous inspirer.
        </p>
        <a
          class="bg-blue-500 text-white w-fit px-4 py-2 rounded text-left lg:text-left lg:w-48 hover:bg-blue-600"
          data-aos="flip-right"
          data-aos-delay="500"
          href="{$urls.base_url}fr/14-melange-magique"
        >
          Découvrir plus..
        </a>
      </div>
    </div>
  </div>

  <!-- Section des articles sous Méeelange Magique -->
  <div
    class="articles-section py-16 bg-gray-100"
    data-aos="fade-up"
    data-aos-duration="1000"
  >
    <div class="container mx-auto px-4">
      <h2
        class="text-3xl font-semibold text-center text-gray-800 mb-8"
        data-aos="fade-down"
        data-aos-delay="100"
      >
        Nos Catégories de Céréales
      </h2>

      <div
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
        data-aos="fade-up"
        data-aos-delay="200"
      >
        <div
          class="article-card bg-white p-6 rounded-lg shadow-lg"
          data-aos="zoom-in"
          data-aos-delay="200"
        >
          <img
            src="{$urls.child_img_url}/rings.png"
            alt="Article Image"
            class="w-full h-[240px] object-cover rounded-lg mb-4"
          />
          <h3 class="text-xl font-semibold text-gray-700">
            Céréales Gourmandes
          </h3>
          <p class="text-gray-600 mt-2">
            Des céréales si délicieuses qu'elles feront BEHH-ler vos papilles !
            Gourmandes, croquantes et pleines de surprises, elles transforment
            chaque petit-déjeuner en un moment magique.
          </p>
          <a href="#" class="text-blue-500 mt-4 inline-block">Lire l'article</a>
        </div>

        <div
          class="article-card bg-white p-6 rounded-lg shadow-lg"
          data-aos="zoom-in"
          data-aos-delay="300"
        >
          <img
            src="{$urls.child_img_url}/creale-bio.png"
            alt="Article Image"
            class="w-full h-[240px] object-cover rounded-lg mb-4"
          />
          <h3 class="text-xl font-semibold text-gray-700">Céréales Bio</h3>
          <p class="text-gray-600 mt-2">
            Parce que chez BEHH, on aime le naturel ! Ces céréales bio sont
            aussi pures que l'air de la montagne où les chèvres dansent en
            criant... BEHH ! Faites le plein de nature à chaque bouchée.
          </p>
          <a href="#" class="text-blue-500 mt-4 inline-block">Lire l'article</a>
        </div>

        <div
          class="article-card bg-white p-6 rounded-lg shadow-lg"
          data-aos="zoom-in"
          data-aos-delay="400"
        >
          <img
            src="{$urls.child_img_url}/powerup.png"
            alt="Article Image"
            class="w-full h-[240px] object-cover rounded-lg mb-4"
          />
          <h3 class="text-xl font-semibold text-gray-700">
            Céréales Énergétiques
          </h3>
          <p class="text-gray-600 mt-2">
            Une dose d'énergie qui fera bondir comme une chèvre le matin ! Ces
            céréales BEHH nergétiques vous boostent pour affronter la journée,
            sans compromis sur le goût.
          </p>
          <a href="#" class="text-blue-500 mt-4 inline-block">Lire l'article</a>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="container mx-auto my-10">
  {hook h="displayProductReco"}
</div>
<section
  class="vision-section bg-yellow-500 py-16"
  data-aos="fade-up"
  data-aos-duration="800"
>
  <div
    class="container mx-auto px-8 lg:px-16 flex flex-col lg:flex-row items-center lg:items-start justify-between gap-8"
  >
    <!-- Image à gauche -->
    <div
      class="flex-shrink-0 w-full lg:w-1/2"
      data-aos="fade-right"
      data-aos-duration="1000"
    >
      <img
        src="{$urls.child_img_url}/chococrunch.png"
        alt="Honey Choco Crunch"
        class="rounded-lg shadow-lg object-contain w-full"
      />
    </div>

    <!-- Texte + bouton à droite -->
    <div
      class="text-section flex flex-col justify-start text-left gap-6 lg:w-1/2"
      data-aos="fade-left"
      data-aos-duration="1000"
    >
      <h2 class="text-3xl lg:text-4xl font-bold text-white leading-tight">
        Notre Vision
      </h2>
      <p class="text-base lg:text-lg text-white leading-relaxed">
        Chez <strong>BEHH</strong>, nous croyons que le petit-déjeuner doit être
        un moment magique, où saveurs délicieuses et nutrition s'unissent pour
        éveiller vos journées. Inspirés par la nature et la joie, nos céréales
        sont conçues pour satisfaire petits et grands tout en respectant
        l'environnement.
      </p>
      <p class="text-base lg:text-lg text-white leading-relaxed">
        Avec un mélange parfait de croquant et de gourmandise, nous apportons un
        sourire à chaque bouchée. Rejoignez notre mission de transformer chaque
        matin en une célébration du goût et de la bonne humeur !
      </p>

      <!-- Bouton aligné au texte -->
      <a
        href="{$urls.base_url}fr/content/7-nutrition"
        class="bg-white text-yellow-500 font-semibold px-4 py-2 rounded-md shadow-md hover:bg-yellow-400 hover:text-white transition-all text-sm lg:text-base"
        data-aos="zoom-in"
        data-aos-duration="800"
      >
        Découvrir nos valeurs
      </a>
    </div>
  </div>
</section>
<section class="relative bg-blue-300 infoAccB">
  <!-- Zone bleue avec les caractéristiques -->
  <div class="container mx-auto px-6 lg:px-12">
    <!-- Conteneur des caractéristiques -->
    <div 
      class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center pt-16"
      data-aos="fade-up" 
      data-aos-duration="1000"
    >
      <!-- Icône 1 -->
      <div class="flex flex-col items-center" data-aos="zoom-in" data-aos-delay="100">
        <div class="bg-white p-4 rounded-full shadow-lg">
          <img class="h-12 w-12 text-blue-500" src="{$urls.child_img_url}/romantic.png" alt="coeur" />
        </div>
        <h3 class="text-xl font-bold mt-4">100% Naturels</h3>
        <p class="text-gray-700 mt-2 text-sm">
          Des céréales sans additifs ni conservateurs, uniquement des
          ingrédients sains.
        </p>
      </div>

      <!-- Icône 2 -->
      <div class="flex flex-col items-center" data-aos="zoom-in" data-aos-delay="300">
        <div class="bg-white p-4 rounded-full shadow-lg">
          <img class="h-12 w-12 text-blue-500" src="{$urls.child_img_url}/writing.png" alt="bloc note" />
        </div>
        <h3 class="text-xl font-bold mt-4">Recette Personnalisée</h3>
        <p class="text-gray-700 mt-2 text-sm">
          Créez vos céréales sur mesure, adaptées à vos goûts et à vos besoins.
        </p>
      </div>

      <!-- Icône 3 -->
      <div class="flex flex-col items-center" data-aos="zoom-in" data-aos-delay="500">
        <div class="bg-white p-4 rounded-full shadow-lg">
          <img class="h-12 w-12 text-blue-500" src="{$urls.child_img_url}/wheat.png" alt="sac" />
        </div>
        <h3 class="text-xl font-bold mt-4">Livraison Éco-responsable</h3>
        <p class="text-gray-700 mt-2 text-sm">
          Livraison rapide, avec un emballage respectueux de l'environnement.
        </p>
      </div>
    </div>
  </div>

  <!-- Vague séparatrice -->
  <div class="relative">
    <svg
      class="w-full h-auto"
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 1440 320"
    >
      <path
        fill="#ffffff"
        d="M0,224L80,213.3C160,203,320,181,480,192C640,203,800,245,960,240C1120,235,1280,181,1360,149.3L1440,117L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"
      ></path>
    </svg>

    <!-- Image flottante des céréales -->
    <div 
      class="absolute -left-12 top-16 transform" 
      
    >
      <img
        class="cereales"
        src="{$urls.child_img_url}/cereales.png"
        alt="Céréales flottantes"
        class="w-48 lg:w-64"
      />
    </div>
  </div>

  <!-- Zone blanche avec le titre -->
  <div class="bg-white py-16">
    <div class="container mx-auto px-6 lg:px-12">
      <!-- Titre aligné à droite -->
      <h2 class="text-4xl md:text-5xl font-bold text-pink-500 text-right z-10"  data-aos="fade-up" data-aos-duration="1000">
        Des céréales sur-mesure, pour nourrir votre corps au quotidien !
      </h2>
    </div>
  </div>

  <svg class="effect">
    <filter id="gooey">
      <feGaussianBlur in="SourceGraphic" stdDeviation="15" result="blur" />
      <feColorMatrix
        in="blur"
        mode="matrix"
        values="1 0 0 0 0
                  0 1 0 0 0
                  0 0 1 0 0
                  0 0 0 30 -7"
        result="gooey"
      />
      <feBlend in="SourceGraphic" in2="gooey" />
    </filter>
  </svg>
  <div class="container-milk">
    <div class="milk">
      <canvas id="monCanvas"></canvas>
      <svg
        class="bolBack bolFront"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        viewBox="0 0 467.56 268.55"
      >
        <defs>
          <style>
            .bowl-1 {
              fill: #4a7088;
            }
  
            .bowl-2 {
              fill: #ced2cc;
            }
  
            .bowl-3 {
              fill: #b4d3e9;
            }
  
            .bowl-4 {
              fill: #f7f7f8;
            }
  
            .bowl-5 {
              fill: #294456;
            }
  
            .bowl-6 {
              fill: #4f7c8c;
            }
  
            .bowl-7 {
              fill: #9ab9cf;
            }
  
            .bowl-8 {
              fill: url(#Dégradé_sans_nom_220);
            }
  
            .bowl-9 {
              fill: url(#Dégradé_sans_nom_219);
            }
  
            .bowl-10 {
              fill: url(#Dégradé_sans_nom_213);
            }
  
            .bowl-11 {
              fill: url(#Dégradé_sans_nom_215);
            }
  
            .bowl-12 {
              fill: #1c3043;
            }
  
            .bowl-13 {
              fill: #4a6a8d;
            }
  
            .bowl-14 {
              fill: #698bac;
            }
  
            .bowl-15 {
              fill: #e2ecf3;
            }
  
            .bowl-16 {
              fill: #d0beb0;
            }
  
            .bowl-17 {
              fill: #9e9e9c;
            }
  
            .bowl-18 {
              fill: #5d7f9a;
            }
  
            .bowl-19 {
              fill: #37586c;
            }
  
            .bowl-20 {
              fill: #83a2bd;
            }
          </style>
          <linearGradient
            id="Dégradé_sans_nom_215"
            data-name="Dégradé sans nom 215"
            x1="243.33"
            y1="143.54"
            x2="358.29"
            y2="226.92"
            gradientUnits="userSpaceOnUse"
          >
            <stop offset="0" stop-color="#6c8fac" />
            <stop offset=".69" stop-color="#314d69" />
            <stop offset="1" stop-color="#4a758b" />
          </linearGradient>
          <linearGradient
            id="Dégradé_sans_nom_219"
            data-name="Dégradé sans nom 219"
            x1="318.27"
            y1="143.75"
            x2="107.19"
            y2="198.73"
            gradientUnits="userSpaceOnUse"
          >
            <stop offset="0" stop-color="#416687" />
            <stop offset=".76" stop-color="#85a6c2" />
            <stop offset="1" stop-color="#b1cfe3" />
          </linearGradient>
          <linearGradient
            id="Dégradé_sans_nom_220"
            data-name="Dégradé sans nom 220"
            x1="46.58"
            y1="122.21"
            x2="446.95"
            y2="144.76"
            gradientUnits="userSpaceOnUse"
          >
            <stop offset="0" stop-color="#c7b3a6" />
            <stop offset=".82" stop-color="#f5f2e2" />
          </linearGradient>
          <linearGradient
            id="Dégradé_sans_nom_213"
            data-name="Dégradé sans nom 213"
            x1="49.36"
            y1="72.81"
            x2="449.73"
            y2="95.36"
            gradientUnits="userSpaceOnUse"
          >
            <stop offset="0" stop-color="#c7b3a6" />
            <stop offset=".82" stop-color="#f5f2e2" />
          </linearGradient>
        </defs>
        <g id="bolfront">
          <path
            class="bowl-11"
            d="M453.77,120.18c-22.32,43.82-59.22,81.95-102.63,105.14-4.47,2.39-27.96,12.24-29.36,13.98-10.46,2-20.61,6.21-31.04,8.39-20.33,4.25-42.7,5.97-63.48,5.59-27.3-.5-50.92-4.89-76.62-13.42C112.6,228.45,6.47,164.69,1.02,84.23c-.06,16.56,28.79,50.8,31.62,52.73,3.59,2.45,7.35,5.23,11.19,7.27-1.91-.2-.95,1.73-.56,2.52,7.65,15.22,46.47,54.39,61.24,63.48,1.87,1.15,1.31-1.12,2.24-.56,13.4,8.14,28.95,18.68,44.18,22.93.36.1,1.94-.92,3.36-.56,11.26,2.86,19.26,4.08,30.76,5.59.53.07.03,1.61.56,1.68,6.08.82,16.07.33,22.37,0,53.5-2.83,102.73-25.84,136.18-67.67,1.93-2.42,3.8-4.73,5.59-7.27.11-.15,1.02-.98,1.12-1.12,17.72-3.31,34.68-8.77,51.45-15.1,5.79-2.18,11.15-4.08,16.78-6.71,6.56-3.07,12.97-6.04,19.02-10.07l-1.12,4.75c1.97,4.85,12.11-12.8,12.86-13.7,1.39-1.68,1.13-2.78,3.91-2.24Z"
          />
          <path
            class="bowl-2"
            d="M466.63,83.83c-.1,6.94-1.8,10.67-3.91,16.78l-16.5,16.22c-6.01,4.26-12.22,8.3-18.74,11.74-3.47-3.71,4.52-3.32,3.91-7.27,16.46-9.24,26.29-18.48,33-36.07.64-1.67.29-2.07,2.24-1.4Z"
          />
          <path
            class="bowl-17"
            d="M431.4,121.3c.6,3.95-7.39,3.56-3.91,7.27-2.82,1.49-8.14,4.79-10.07,5.59-.84.35-4.16.77-5.03,1.12-12.09,4.77-26.22,9.03-39.15,12.86-4.36,1.29-8.36,2.78-12.86,3.91-6.57-5.52,8.64-6.62,12.58-7.83,19.07-5.86,41.06-13.17,58.44-22.93Z"
          />
          <path
            class="bowl-4"
            d="M109.25,152.62c-5.74-1.35-11.67-2.85-17.34-4.47-16.38-4.7-35.82-11.7-50.89-19.57-4.56-2.38-10.35-5.5-14.54-8.39-6.77-4.68-17.31-14.01-21.25-21.25,17.47,17.06,37.27,27.49,59.56,36.91,5.92,2.5,13.33,4.21,18.46,6.15,2.26.86,6.95,2.45,6.71,4.47l19.57,4.47-.28,1.68Z"
          />
          <path
            class="bowl-19"
            d="M462.72,100.61c-2.35,6.81-5.69,13.18-8.95,19.57-2.79-.54-2.52.56-3.91,2.24-4.04,2.86-7.61,6.2-11.74,8.95-6.05,4.02-12.45,7-19.02,10.07l-1.68-7.27c1.93-.8,7.24-4.1,10.07-5.59,6.52-3.44,12.72-7.49,18.74-11.74l16.5-16.22Z"
          />
          <path
            class="bowl-13"
            d="M5.23,98.93c3.94,7.24,14.48,16.58,21.25,21.25l2.8,8.95c-3.56-2.54-7.78-5.37-10.91-8.39-4.26-4.11-6.72-7.82-10.35-12.3-1.49-3.41-3.83-8.03-3.91-11.74.3.69.75,1.56,1.12,2.24Z"
          />
          <path
            class="bowl-14"
            d="M29.28,129.13c.51.36,1.51,1.56,1.68,1.68l1.68,6.15c-2.83-1.93-5.7-4.68-8.95-5.59-1.57-2.15-4.39-8.22-7.27-6.15-2.98-5.55-5.86-10.99-8.39-16.78,3.62,4.49,6.09,8.19,10.35,12.3,3.13,3.02,7.35,5.85,10.91,8.39Z"
          />
          <g>
            <path
              class="bowl-12"
              d="M321.78,239.31c-.22.27-1.83,5.19-2.8,6.43-8.14,10.42-37.9,16.82-50.89,18.74l.28-5.59c-4.79,1.18-9.91,1.45-14.82,1.68.05-.9.2-4.7-.28-5.03-1.21-.86-21.44,1.81-25.17.56-.14-.05-1.39-2.26-.84-2.8,20.78.38,43.15-1.34,63.48-5.59,10.43-2.18,20.58-6.39,31.04-8.39Z"
            />
            <path
              class="bowl-1"
              d="M253.55,260.56c-.19,3.26-2.26,4.74-2.24,5.59-16.75,1.06-33.16.49-49.78-1.68-13.16-1.72-40.58-6.72-50.34-15.66-1.34-1.23-2.41-2.91-3.36-4.47,1.88-.37,5.94,2.46,7.55,2.8,3.98.83,7.48.58,11.19,1.68s7.09,2.06,11.19,3.36c.17.05.78,1.64,2.8,2.24,21.41,6.35,50.68,7.2,72.99,6.15Z"
            />
            <path
              class="bowl-19"
              d="M150.27,239.18c25.7,8.53,49.69,13.61,76.99,14.11-.55.53.7,2.75.84,2.8,3.73,1.25,23.96-1.42,25.17-.56.48.34.33,4.14.28,5.03-22.3,1.05-51.58.2-72.99-6.15-2.02-.6-2.63-2.18-2.8-2.24-4.09-1.3-7.38-2.23-11.19-3.36s-7.2-.85-11.19-1.68c-1.61-.34-5.67-3.16-7.55-2.8-1.07-1.78-2.66-4.04-2.61-6.28,1.37.49,3.61.64,5.03,1.12Z"
            />
            <path
              class="bowl-12"
              d="M268.09,264.48c-2,.29-4.15.33-6.15.56-3.56.41-7.03.89-10.63,1.12-.02-.85,2.05-2.33,2.24-5.59,4.91-.23,10.03-.5,14.82-1.68l-.28,5.59Z"
            />
          </g>
          <path
            class="bowl-18"
            d="M91.91,148.15c5.67,1.62,11.6,3.12,17.34,4.47,14.93,3.51,30.08,5.86,45.3,7.83-.39,2.15.4,8.02,1.96,8.95,50.78,5.71,103.54,6.54,154.36.56,12.99-1.53,25.81-4.91,38.87-5.59-1.79,2.54-3.66,4.85-5.59,7.27-39.93,7.23-81.53,10.16-122.2,9.51-36.23-.58-72.61-4.13-107.66-12.86l-.84-6.71c-7.42-1-14.4-3.55-21.53-5.59,2.95-.81-3.99-6.72,0-7.83Z"
          />
          <path
            class="bowl-19"
            d="M350.86,163.25c-.1.14-1.01.97-1.12,1.12-13.06.69-25.88,4.06-38.87,5.59-50.82,5.98-103.58,5.15-154.36-.56-1.55-.93-2.35-6.8-1.96-8.95,64.21,8.3,137.61,7.47,200.78-7.27,1.59,2.31-2.85,7.7-4.47,10.07Z"
          />
          <path
            class="bowl-12"
            d="M403.43,139.76c-4.07,2.61-4.37,3.39-1.12,8.39-16.77,6.33-33.73,11.79-51.45,15.1,1.62-2.37,6.07-7.75,4.47-10.07,1.67-.39,3.37-.7,5.03-1.12,4.5-1.13,8.51-2.62,12.86-3.91l30.2-8.39Z"
          />
          <path
            class="bowl-9"
            d="M43.82,144.23c1.23.65,3.73,1.03,3.91,1.12,2.65,1.26,4.82,3.34,7.55,4.47l.28-2.24c.48,1.01,2.59,3.72,3.36,5.03,15.38,26.46,49.15,53.67,78.3,62.64,2.44.75,19.9,5.18,20.97,5.03.77-.1,1.56-.54,1.4-1.4-.27-1.41-21.92-10.38-25.73-12.58-21.14-12.23-36.08-26.22-50.34-45.86,10.54,1.39,20.42,5.25,30.76,7.83,35.05,8.73,71.43,12.28,107.66,12.86,40.67.66,82.27-2.27,122.2-9.51-33.45,41.83-82.68,64.85-136.18,67.67-6.3.33-16.29.82-22.37,0-.53-.07-.03-1.61-.56-1.68-11.5-1.51-19.5-2.74-30.76-5.59-1.42-.36-3,.66-3.36.56-15.24-4.25-30.78-14.79-44.18-22.93-.92-.56-.37,1.71-2.24.56-14.77-9.09-53.59-48.26-61.24-63.48-.39-.78-1.35-2.72.56-2.52Z"
          />
          <path
            class="bowl-5"
            d="M417.42,134.16l1.68,7.27c-5.63,2.63-10.99,4.53-16.78,6.71-3.25-5-2.95-5.78,1.12-8.39,3.1-1.98,6.19-2.21,8.95-4.47.87-.34,4.2-.77,5.03-1.12Z"
          />
          <path
            class="bowl-20"
            d="M43.26,137.52c.73.37,2.11,1.62,2.24,1.68l2.24,6.15c-.18-.09-2.69-.47-3.91-1.12-3.83-2.04-7.59-4.82-11.19-7.27l-1.68-6.15c3.75,2.55,8.26,4.63,12.3,6.71Z"
          />
          <path
            class="bowl-6"
            d="M449.85,122.42c-.75.9-10.89,18.55-12.86,13.7l1.12-4.75c4.14-2.75,7.71-6.09,11.74-8.95Z"
          />
          <path
            class="bowl-18"
            d="M41.02,128.57l2.24,8.95c-4.04-2.08-8.55-4.17-12.3-6.71-.17-.11-1.17-1.31-1.68-1.68l-2.8-8.95c4.19,2.89,9.98,6.01,14.54,8.39Z"
          />
          <path
            class="bowl-14"
            d="M91.91,148.15c-3.99,1.11,2.95,7.02,0,7.83-4.23-1.21-8.61-2.22-12.86-3.36l-1.12-1.12c-1.13-6.85-4.94-5.4-8.67-7.27-4.13-2.07-3.89-1.81-7.83-3.36-1.69-.66-9.99-6.31-10.91-3.64-.1.29,3.39,3.79,1.96,5.31-2.1-1.56-4.67-2.26-6.99-3.36-.13-.06-1.51-1.3-2.24-1.68l-2.24-8.95c15.07,7.87,34.51,14.88,50.89,19.57Z"
          />
          <path
            class="bowl-7"
            d="M91.91,155.98c7.13,2.04,14.11,4.59,21.53,5.59l.84,6.71c-10.34-2.58-20.22-6.44-30.76-7.83-.41-.57-.73-1.13-1.12-1.68-1.24-1.75-3.12-3.96-3.36-6.15,4.26,1.14,8.63,2.14,12.86,3.36Z"
          />
          <path
            class="bowl-8"
            d="M412.38,135.28c-2.76,2.27-5.85,2.49-8.95,4.47l-30.2,8.39c12.93-3.83,27.06-8.1,39.15-12.86Z"
          />
          <path
            class="bowl-15"
            d="M82.41,158.77c.39.55.71,1.11,1.12,1.68,14.26,19.64,29.19,33.63,50.34,45.86-.75,3.93,2.64,5.48,3.36,8.95-29.14-8.97-62.92-36.18-78.3-62.64l23.49,6.15Z"
          />
          <path
            class="bowl-7"
            d="M77.93,151.5c-3.59.49-23.05-8.95-24.05-7.55,1,.91,1.18,2.6,1.68,3.64l-.28,2.24c-2.73-1.13-4.9-3.22-7.55-4.47l-2.24-6.15c2.32,1.1,4.89,1.79,6.99,3.36,1.43-1.52-2.06-5.02-1.96-5.31.92-2.67,9.22,2.97,10.91,3.64,3.94,1.55,3.7,1.29,7.83,3.36,3.73,1.87,7.54.42,8.67,7.27Z"
          />
          <path
            class="bowl-3"
            d="M77.93,151.5l1.12,1.12c.23,2.19,2.11,4.41,3.36,6.15l-23.49-6.15c-.77-1.32-2.87-4.02-3.36-5.03s-.68-2.73-1.68-3.64c1-1.4,20.46,8.04,24.05,7.55Z"
          />
          <path
            class="bowl-3"
            d="M137.22,215.26c-.71-3.46-4.11-5.02-3.36-8.95,3.8,2.2,25.46,11.18,25.73,12.58.16.86-.63,1.3-1.4,1.4-1.07.14-18.53-4.28-20.97-5.03Z"
          />
        </g>
      </svg>
      <svg
        class="bolBack"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        viewBox="0 0 467.56 268.55"
      >
        <defs>
          <style>
            .bowl-10 {
              fill: url(#Dégradé_sans_nom_213);
            }
  
            .bowl-16 {
              fill: #d0beb0;
            }
          </style>
          <linearGradient
            id="Dégradé_sans_nom_213"
            data-name="Dégradé sans nom 213"
            x1="49.36"
            y1="72.81"
            x2="449.73"
            y2="95.36"
            gradientUnits="userSpaceOnUse"
          >
            <stop offset="0" stop-color="#c7b3a6" />
            <stop offset=".82" stop-color="#f5f2e2" />
          </linearGradient>
        </defs>
        <g id="bolback">
          <path
            class="bowl-10"
            d="M466.63,83.83c-1.95-.67-1.6-.27-2.24,1.4-6.71,17.6-16.54,26.84-33,36.07-17.39,9.76-39.37,17.07-58.44,22.93-3.95,1.21-19.15,2.31-12.58,7.83-1.67.42-3.36.73-5.03,1.12-63.17,14.74-136.57,15.57-200.78,7.27-15.22-1.97-30.37-4.32-45.3-7.83l.28-1.68-19.57-4.47c.24-2.02-4.45-3.62-6.71-4.47-5.12-1.95-12.54-3.65-18.46-6.15-22.29-9.42-42.09-19.85-59.56-36.91-.37-.68-.82-1.55-1.12-2.24C-19.23,42.43,93.64,15.54,128.55,9.44,212.98-5.31,325.9-2.35,406.51,27.9c23.19,8.7,60.57,25.78,60.12,55.93Z"
          />
          <path
            class="bowl-16"
            d="M58.92,82.71c7.77,11.94,58.61,48.1,47.28,48.1-25.79.43-42.05-2.65-74.69-21.25C-7.19,80.97,15.03,55.7,49.13,39.09c-6.48,12.61,1.77,33.4,9.79,43.62Z"
          />
        </g>
      </svg>
      <div class="bottleFront">
        <svg
          class="bottle back"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          viewBox="174 0 320.1 647.55"
        >
          <defs>
            <style>
              .cls-1 {
                fill: #94c6e6;
              }
  
              .cls-2 {
                fill: #15548f;
              }
  
              .cls-3 {
                fill: #f1e7e1;
              }
  
              .cls-4,
              .cls-5,
              .cls-6,
              .cls-7 {
                opacity: 0.2;
              }
  
              .cls-4,
              .cls-8 {
                fill: #afd2db;
              }
  
              .cls-9 {
                fill: #e6f3f6;
              }
  
              .cls-10,
              .cls-6 {
                fill: #cbe6ee;
              }
  
              .cls-11 {
                fill: #d3c2b9;
              }
  
              .cls-5 {
                fill: #7ab9dd;
              }
  
              .cls-12 {
                fill: #fff;
              }
  
              .cls-13 {
                fill: #8ac0dd;
              }
  
              .cls-14 {
                fill: #2e8fbd;
              }
  
              .cls-15 {
                fill: url(#Dégradé_sans_nom_34);
              }
  
              .cls-16 {
                fill: #a8d4e1;
              }
  
              .cls-17 {
                fill: #60aabe;
              }
  
              .cls-7 {
                fill: #a4d1ea;
              }
            </style>
            <linearGradient
              id="Dégradé_sans_nom_34"
              data-name="Dégradé sans nom 34"
              x1="210.39"
              y1="215.49"
              x2="211.84"
              y2="397.91"
              gradientTransform="translate(11.86 656.8) rotate(-90)"
              gradientUnits="userSpaceOnUse"
            >
              <stop offset="0" stop-color="#77b8dc" />
              <stop offset=".89" stop-color="#2282b3" />
            </linearGradient>
          </defs>
          <g id="Calque_3" data-name="Calque 3" class="back">
            <path
              class="cls-13"
              d="M393.44,23.4s3.91,39.18,2.92,39.79c-1.13.7-9.86,3.25-16.52,5.26-1.08.33,1.61-40.63.23-40.3.18-4.92.96-9.61,1.98-13.14,5.76,2.46,9.81,5.29,11.39,8.39Z"
            />
            <path
              class="cls-1"
              d="M381.91,28.12c-.17,10.9,8.57,31.06-2.81,39.32-9.64,7-38.56,6.19-51.18,6.01-22.68-.32-50.35-2.7-59.97-11.8-16.7-15.8-4.75-45.14-4.75-45.14,21.29-16.82,90.62-13.57,118.85-1.5-.41,3.88-1.62,7.72-.14,13.11Z"
            />
            <path
              class="cls-10"
              d="M382.05,15.01c-28.23-12.07-97.56-15.32-118.85,1.5l-.24-.04s-.09-.02-.15-.03l-2.78-.5c-.21-.04-.44-.08-.68-.12-.2-.03-.41-.07-.63-.11l-2.74-.49c-.08-.18-.15-.19-.21-.04h-.02c4.05-12.86,80.24-22.92,125.84-7.02.12.99.22,2.08.3,3.25.07,1.12.12,2.32.16,3.6Z"
            />
            <path
              class="cls-8"
              d="M405.57,22.06l-8.49.94-3.64.4c-1.58-3.1-5.63-5.93-11.39-8.39-.04-1.28-.09-2.48-.16-3.6-.08-1.17-.18-2.26-.3-3.25,9.71,3.38,18.04,7.94,23.98,13.9Z"
            />
          </g>
        </svg>
        <svg
          class="bottle"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          viewBox="174 0 320.1 647.55"
        >
          <defs>
            <style>
              .cls-1 {
                fill: #94c6e6;
              }
  
              .cls-2 {
                fill: #15548f;
              }
  
              .cls-3 {
                fill: #f1e7e1;
              }
  
              .cls-4,
              .cls-5,
              .cls-6,
              .cls-7 {
                opacity: 0.87;
              }
  
              .cls-4,
              .cls-8 {
                fill: #afd2db;
              }
  
              .cls-9 {
                fill: #e6f3f6;
              }
  
              .cls-10,
              .cls-6 {
                fill: #cbe6ee;
              }
  
              .cls-11 {
                fill: #d3c2b9;
              }
  
              .cls-5 {
                fill: #7ab9dd;
              }
  
              .cls-12 {
                fill: #fff;
              }
  
              .cls-13 {
                fill: #8ac0dd;
              }
  
              .cls-14 {
                fill: #2e8fbd;
              }
  
              .cls-15 {
                fill: url(#Dégradé_sans_nom_34);
              }
  
              .cls-16 {
                fill: #a8d4e1;
              }
  
              .cls-17 {
                fill: #60aabe;
              }
  
              .cls-7 {
                fill: #a4d1ea;
              }
            </style>
            <linearGradient
              id="Dégradé_sans_nom_34"
              data-name="Dégradé sans nom 34"
              x1="210.39"
              y1="215.49"
              x2="211.84"
              y2="397.91"
              gradientTransform="translate(11.86 656.8) rotate(-90)"
              gradientUnits="userSpaceOnUse"
            >
              <stop offset="0" stop-color="#77b8dc" />
              <stop offset=".89" stop-color="#2282b3" />
            </linearGradient>
          </defs>
          <g id="Calque_1" data-name="Calque 1">
            <g id="Objet_génératif" data-name="Objet génératif">
              <path
                class="cls-12"
                d="M455.46,198.47c21.62,32.67,33.63,64.2,35.9,103.83-3.61,10,1.55,77.46-.97,81.02-.8,1.12-6.07.18-7.76.49-.67-74.96,7.53-114.67-34.93-180.48l7.76-4.85Z"
              />
              <path
                class="cls-10"
                d="M482.63,383.81c1.69-.3,6.97.64,7.76-.49,2.53-3.56-2.64-71.02.97-81.02,2.98,52.01,1,110.42.97,163.02l-9.7.97c.11-27.46.25-55.02,0-82.48Z"
              />
              <g>
                <path
                  class="cls-1"
                  d="M266.24,64.57c.28.12,5.44,1.84,5.82,1.94-.89,22.5,2.76,44.77-3.88,66.47-13.24,43.25-56.08,72.75-70.83,115.47-2.99,8.67-9.49,33.74-5.82,41.72,2.3,5,31.8,17.68,35.9,4.85,2.92-9.13,3.35-24.01,6.79-35.42,1.88-6.22,15.91-35.37,15.53-37.36-1.11-5.82-16.38-3.26-20.38-.49v-.97c14.64-22.97,34.17-45.48,43.67-71.32,10.09-27.46,7.81-53.23,7.76-81.99,15.71,2.8,34.52,3.96,50.46,3.88-16.67,26.35-28.05,55.01-36.87,84.9-13.16,44.59-32.14,111.41-39.78,156.22-2.32,13.58-3.04,27.46-2.91,41.24-10.71-1.49-21.43-3.69-32.02-5.82-.16-13.64-1.89-27.51-.97-41.24-2.99-.86-23.4-8.02-24.26-5.82v278.49c1.85,9.88,20.95,21.19,24.26,10.67,2.07-6.59-.09-47.21,0-57.74,1.74.66,4.08,1.55,5.82,1.94,4.09,19.25,0,68.64,12.61,82.96,3.33,3.78,5.41,2.86,8.73,4.37,3.98,1.8,7.44,2.79,11.64,3.88l-2.91,7.76c-19.56-3.78-70.17-15.38-78.65-35.61.21-2.13.05-.78,1.02-4.66,5.82-97.56-7.77-204.3,0-300.8,4.98-61.91,36.82-86.63,67.92-132.94,22.59-33.63,23.85-53.76,21.35-94.61Z"
                />
                <path
                  class="cls-16"
                  d="M303.14,645.94c4.33-.61,116.46-6.38,117.87-7.91,1.55-1.68-3.82-4.11,2.43-7.76,15.14-3.94,53.35-15.09,58.22-31.54,5.81-19.65.86-105.7.97-132.45l9.7-.97c-.02,38.73,4.1,91.66,0,128.57-.78,7.04-2.8,13.13-6.79,18.92l-53.85,22.32,5.34,4.85c-17.47,3.61-29.68,4.99-45.12,5.82-27.1,1.46-89.2,3.01-88.76.14Z"
                />
                <path
                  class="cls-9"
                  d="M178.91,598.25c8.48,20.22,56.12,31.15,75.69,34.93,55.46,10.72,114.06,11.35,168.84-2.91-6.25,3.65-.88,6.08-2.43,7.76-1.4,1.52-29.14,5.22-33.48,5.82-50.61,7.07-150.87,4.81-194.55-23.29-8.68-5.58-18-12.95-16.98-23.77l2.91,1.46Z"
                />
                <path
                  class="cls-10"
                  d="M405.97,134.43c11.38,21.11,35.18,42.43,49.49,64.04l-7.76,4.85c-14.31-22.19-35.11-42.6-47.55-65.01l5.82-3.88Z"
                />
                <path
                  class="cls-16"
                  d="M397.24,61.66c3.62,26.81-4.64,47.98,8.73,72.78l-5.82,3.88c-3.16-5.69-9.78-17.75-10.67-23.77-2.29-15.36,2.49-34.32-.97-49.97,1.42-.46,8.2-2.63,8.73-2.91Z"
                />
                <path
                  class="cls-16"
                  d="M485.54,612.81c-8.76,12.71-34.04,23.02-48.52,27.17l-5.34-4.85,53.85-22.32Z"
                />
                <path
                  class="cls-17"
                  d="M257.51,625.42c45.6,11.86,109.77,8.28,155.25-2.91,16.15-3.98,55.73-13.77,60.16-31.54-2.04-92.53,5.62-189.34,1.94-281.4-3.08-76.99-44.14-97.51-77.63-155.25-17.13-29.53-19.48-53.71-15.53-87.82,1.95-.45,4.83-1.31,6.79-1.94,3.46,15.65-1.32,34.61.97,49.97.9,6.02,7.52,18.08,10.67,23.77,12.43,22.41,33.23,42.83,47.55,65.01,42.46,65.82,34.26,105.52,34.93,180.48.25,27.46.11,55.01,0,82.48-.11,26.75,4.84,112.8-.97,132.45-4.87,16.45-43.08,27.6-58.22,31.54-54.78,14.26-113.38,13.64-168.84,2.91l2.91-7.76Z"
                />
                <path
                  class="cls-9"
                  d="M219.67,347.91c.23,19.86-1.04,40.19-.97,60.16.15,41.37.35,82.84,0,124.2-.09,10.52,2.07,51.15,0,57.74-3.31,10.52-22.41-.79-24.26-10.67v-278.49c.86-2.19,21.26,4.96,24.26,5.82-.92,13.73.81,27.6.97,41.24Z"
                />
                <path
                  class="cls-9"
                  d="M272.06,66.51c.47.13,6.6.59,8.73.97.05,28.76,2.33,54.54-7.76,81.99-9.5,25.84-29.02,48.35-43.67,71.32-3.33,5.22-8.82,10.55-11.64,16.01-7.85,15.15-13.34,38.54-9.22,54.82,2.85-18.78,5.67-34.46,13.1-52.4,3.97,9.86,15.67,8.89,12.61,20.38-3.44,11.41-3.87,26.29-6.79,35.42-4.11,12.83-33.61.15-35.9-4.85-3.67-7.98,2.83-33.06,5.82-41.72,14.76-42.72,57.6-72.23,70.83-115.47,6.64-21.7,2.99-43.97,3.88-66.47Z"
                />
                <path
                  class="cls-16"
                  d="M221.61,239.23c2.47-5.98,5.28-11.53,7.76-17.47,3.99-2.77,19.26-5.34,20.38.49.38,1.99-13.65,31.14-15.53,37.36,3.05-11.49-8.65-10.52-12.61-20.38Z"
                />
                <path
                  class="cls-12"
                  d="M229.37,220.79v.97c-2.48,5.94-5.29,11.49-7.76,17.47-7.43,17.94-10.25,33.61-13.1,52.4-4.13-16.28,1.37-39.67,9.22-54.82,2.83-5.46,8.32-10.79,11.64-16.01Z"
                />
                <g>
                  <path
                    class="cls-12"
                    d="M331.26,71.36c3.82-.02,8.58-.8,12.61-.97,6.84-.29,13.6-1.12,20.38-1.94.97,95.54-10.36,190.88-45.61,279.94-1.59,4.03-6.21,5.42-3.88,10.19-6.6,0-12.89-.69-19.41-.97.37-1.58,2.62-.53,2.43-2.91-.63-7.84-17.63-24.53-15.04-35.42,1.19-4.99,24.05-19.38,26.68-26.68l-7.28,11.16c18.2-12.44,28.24-41.49,24.26-63.07-1.5-8.13-10.55-19.95-10.67-26.2-.34-16.81,20.98-56.15,23.29-77.63.97-9.01,1.46-22.89-5.34-29.6,8.8,24.97-12.5,39.86-19.89,59.68-4.85,13-10.15,41.99-7.76,55.31.9,5.02,6.05,13.56,6.79,18.44,4.4,28.8-22.75,36.64-36.87,56.28-16.34,22.72-18.45,43.09,6.79,59.68-10.43-.76-20.69-1.47-31.05-2.91-.13-13.78.6-27.66,2.91-41.24,7.64-44.81,26.63-111.63,39.78-156.22,8.82-29.89,20.2-58.55,36.87-84.9Z"
                  />
                  <path
                    class="cls-3"
                    d="M292.44,543.91c41.97,2.3,85.7-1.44,127.11-7.76-.94,28.92-3.83,57.63-6.79,86.36-45.48,11.19-109.65,14.78-155.25,2.91-4.2-1.09-7.66-2.08-11.64-3.88,29.87-11.05,36.79-50.82,46.58-77.63Z"
                  />
                  <path
                    class="cls-12"
                    d="M224.52,534.21c.31.07.66-.07.97,0,20.43,4.82,45.99,8.56,66.95,9.7-9.78,26.81-16.71,66.58-46.58,77.63-3.33-1.5-5.41-.59-8.73-4.37-12.61-14.32-8.53-63.71-12.61-82.96Z"
                  />
                  <path
                    class="cls-11"
                    d="M377.83,66.51c1.2-.24,2.7.27,3.88,0-3.95,34.1-1.6,58.29,15.53,87.82,33.49,57.74,74.55,78.27,77.63,155.25,3.68,92.06-3.98,188.87-1.94,281.4-4.43,17.77-44.01,27.56-60.16,31.54,2.96-28.73,5.85-57.44,6.79-86.36.01-.32-.01-.65,0-.97l19.41-4.37v-190.19c-6.62,1.07-12.91,3.78-19.41,5.34-1.02-21.72-4.04-44.11-7.76-65.5-12.41-71.26-44-140.19-33.96-213.96Z"
                  />
                  <path
                    class="cls-3"
                    d="M364.25,68.45c5-.6,8.53-.94,13.58-1.94-10.03,73.77,21.55,142.7,33.96,213.96,3.73,21.39,6.75,43.78,7.76,65.5-33.99,8.16-69.71,12.57-104.8,12.61-2.33-4.77,2.29-6.16,3.88-10.19,35.24-89.06,46.58-184.41,45.61-279.94Z"
                  />
                  <path
                    class="cls-3"
                    d="M295.35,357.61c-4.31-.19-8.39-.66-12.61-.97-25.24-16.59-23.13-36.96-6.79-59.68,14.13-19.64,41.27-27.48,36.87-56.28-.74-4.87-5.89-13.42-6.79-18.44-2.39-13.32,2.92-42.31,7.76-55.31,7.39-19.82,28.69-34.7,19.89-59.68,6.8,6.71,6.3,20.59,5.34,29.6-2.31,21.48-23.63,60.82-23.29,77.63.13,6.25,9.17,18.07,10.67,26.2,3.98,21.58-6.06,50.63-24.26,63.07l7.28-11.16c-2.64,7.3-25.5,21.69-26.68,26.68-2.59,10.88,14.41,27.58,15.04,35.42.19,2.38-2.06,1.33-2.43,2.91Z"
                  />
                </g>
                <g>
                  <path
                    class="cls-14"
                    d="M218.7,408.07l6.79,126.14c-.31-.07-.66.07-.97,0-1.74-.39-4.08-1.28-5.82-1.94.35-41.36.15-82.84,0-124.2Z"
                  />
                  <path
                    class="cls-2"
                    d="M419.56,345.96c6.5-1.56,12.78-4.26,19.41-5.34v190.19s-19.41,4.37-19.41,4.37c1.88-59.8,2.79-129.69,0-189.22Z"
                  />
                  <g>
                    <path
                      class="cls-15"
                      d="M219.67,347.91c10.59,2.14,21.31,4.33,32.02,5.82,10.36,1.44,20.62,2.16,31.05,2.91,4.22.31,8.3.79,12.61.97,6.52.28,12.81.98,19.41.97,35.08-.05,70.81-4.45,104.8-12.61,2.79,59.53,1.88,129.41,0,189.22-.01.32.01.65,0,.97-41.41,6.33-85.14,10.06-127.11,7.76-20.96-1.15-46.52-4.88-66.95-9.7l-6.79-126.14c-.07-19.97,1.2-40.3.97-60.16Z"
                    />
                    <g>
                      <path
                        class="cls-12"
                        d="M298.26,481.33c-.49-1.1.92-10.78,1.46-11.16,3.93-2.77,78.26.77,89.27-2.91,2.15.77,2.25,9.68,0,10.67-6.57,2.91-46.78-1.71-56.76,1.46,6.82,2.34,56.71,5.24,58.22,7.76.32.54.12,6.55-.49,7.28-1.85,2.2-50.11,7.06-56.28,11.64l56.28-2.91c.61.43.81,7.51.49,8.25-1.84,4.15-80.27,2.85-90.73,4.37-.75-.53-.87-12.54,0-13.58,1.95-2.33,52.91-7.76,59.68-11.16-7.43-.88-59.31-5.62-61.13-9.7Z"
                      />
                      <path
                        class="cls-12"
                        d="M298.26,389.14c-.45-1.07-.54-8.98,1.46-10.19,2.26-.84,39.6,15.77,45.61,15.53,6.55-.27,41.35-22.09,43.67-21.35,2.39,2.64,2.21,10.19,0,12.61-3.29,3.61-30.03,11.65-35.42,16.98l35.42-1.46c1.97,1.07,1.88,8.26,1.46,9.22-1.85,4.14-80.46,2.28-90.73,3.4-.6-.43-.82-8.55-.49-9.22.91-1.85,35.67-.34,40.75-1.94-5.2-.8-40.77-11.3-41.72-13.58Z"
                      />
                      <path
                        class="cls-12"
                        d="M390.45,415.83l-1.46,22.32-87.82,1.94c-1.75-2.45-5.89-4.62-1.46-10.67,9.88-1.26,72.55.95,76.66-1.94,1.69-1.19-1.19-11.23,1.94-12.61,1.34-.6,10.39-.1,12.13.97Z"
                      />
                      <path
                        class="cls-12"
                        d="M331.26,447.85c13.43-.76,27.34-1.78,40.75-1.94,5.45-.06,11.82.83,16.98-.97v10.67c-10.88-1.03-85.98,6.51-89.76,3.4-.62-.51-2.12-9.03.49-10.19,1.44-.64,26.67-.69,31.54-.97Z"
                      />
                      <path
                        class="cls-14"
                        d="M301.17,440.09l87.82-1.94,1.46-22.32c3.08,1.9,3.23,22.82-.49,24.26-3.64,1.41-11.7-1.94-14.56-.97-.39.13,2.12,2.43.49,4.37-1.08,1.27-4.39-.86-3.88,2.43-13.41.16-27.33,1.18-40.75,1.94-1.13-5.58,4.59-2.12,7.76-2.43,2.93-6.39-36.52-1.64-38.33-2.43-1.05-.46,1.26-1.83.49-2.91Z"
                      />
                    </g>
                  </g>
                </g>
              </g>
            </g>
            <path
              class="cls-4"
              d="M405.57,22.06c-1.46,2.91-4.37,5.36-8.48,7.39-4.06,2.01-9.29,3.6-15.45,4.78.11-2.11.2-4.15.27-6.11,9.51-2.28,11.53-4.72,11.53-4.72l3.64-.4,8.49-.94Z"
            />
            <path
              class="cls-6"
              d="M381.91,28.12c-.07,1.96-.16,4-.27,6.11h0c-12.53,2.42-28.91,3.19-47.07,2.48-24.2-.95-49.57-1.64-64.76-7.6-7.47-2.93-12.48-7.13-13.67-13.27-.06-.29-.11-.5-.16-.62l2.74.49c.22.04.43.08.63.11.24.04.47.08.68.12l2.78.5c.06,0,.11.02.15.03l.24.04s4.12,14.54,66.18,15.42c27.53.39,43.45-1.64,52.53-3.81Z"
            />
            <path
              class="cls-5"
              d="M406.84,51.74c.06,6.94-8.11,13.38-27.74,17.42.7-8.24,1.34-16.2,1.85-23.59.18-2.65.35-5.22.49-7.71.07-1.23.14-2.43.2-3.62h0c6.16-1.19,11.39-2.78,15.45-4.79,4.11-2.03,7.02-4.48,8.48-7.39,2.63,1.82,1.16,17.94,1.27,29.68Z"
            />
            <path
              class="cls-7"
              d="M381.64,34.24c-.06,1.19-.13,2.39-.2,3.62-.14,2.49-.31,5.06-.49,7.71-.51,7.39-1.15,15.35-1.85,23.59-13.59,2.8-32.67,4.44-58.32,4.3-62.68-.36-65.55-14.64-65.71-22.13-.13-6.57-.15-33.85.68-36.1.01-.02.01-.04.02-.05l.21.04c.05.12.1.33.16.62,1.19,6.14,6.2,10.34,13.67,13.27,15.19,5.96,40.56,6.65,64.76,7.6,18.16.71,34.54-.06,47.07-2.47Z"
            />
          </g>
        </svg>
      </div>
    </div>
  </div>
</section>



<section class="py-16 bg-red-500">
  <div class="container mx-auto px-6 lg:px-12">
    <!-- Titre de la section -->
    <h2 
      class="text-3xl md:text-4xl font-bold text-white text-center mb-8"
      data-aos="fade-down"
      data-aos-duration="1000"
    >
      Ce que disent nos clients
    </h2>

    <!-- Grille des avis -->
    <div 
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
      data-aos="fade-up"
      data-aos-duration="1000"
    >
      <!-- Avis 1 -->
      <div 
        class="bg-white p-6 rounded-lg shadow-md" 
        data-aos="zoom-in"
        data-aos-delay="100"
      >
        <p class="text-gray-800 italic mb-4">
          "Les céréales sont incroyablement délicieuses ! Je les commande chaque
          mois pour toute ma famille."
        </p>
        <div class="flex items-center">
          <img
            src="{$urls.child_img_url}/BEHH.png"
            alt="Avatar utilisateur"
            class="w-12 h-12 rounded-full mr-4"
          />
          <div>
            <p class="text-red-600 font-bold">Sophie L.</p>
            <p class="text-gray-600 text-sm">Paris, France</p>
          </div>
        </div>
      </div>

      <!-- Avis 2 -->
      <div 
        class="bg-white p-6 rounded-lg shadow-md" 
        data-aos="zoom-in"
        data-aos-delay="300"
      >
        <p class="text-gray-800 italic mb-4">
          "La livraison est super rapide, et le goût est toujours au
          rendez-vous. Une belle découverte !"
        </p>
        <div class="flex items-center">
          <img
            src="{$urls.child_img_url}/BEHH.png"
            alt="Avatar utilisateur"
            class="w-12 h-12 rounded-full mr-4"
          />
          <div>
            <p class="text-red-600 font-bold">Julien M.</p>
            <p class="text-gray-600 text-sm">Lyon, France</p>
          </div>
        </div>
      </div>

      <!-- Avis 3 -->
      <div 
        class="bg-white p-6 rounded-lg shadow-md" 
        data-aos="zoom-in"
        data-aos-delay="500"
      >
        <p class="text-gray-800 italic mb-4">
          "Des céréales personnalisées et si savoureuses ! J'adore pouvoir
          choisir mes ingrédients préférés."
        </p>
        <div class="flex items-center">
          <img
            src="{$urls.child_img_url}/BEHH.png"
            alt="Avatar utilisateur"
            class="w-12 h-12 rounded-full mr-4"
          />
          <div>
            <p class="text-red-600 font-bold">Claire D.</p>
            <p class="text-gray-600 text-sm">Marseille, France</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<script src="{$urls.child_js_url}/custom-home.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var Engine = Matter.Engine,
      Render = Matter.Render,
      Runner = Matter.Runner,
      MouseConstraint = Matter.MouseConstraint,
      Mouse = Matter.Mouse,
      Composite = Matter.Composite,
      Bodies = Matter.Bodies,
      Composites = Matter.Composites,
      Common = Matter.Common,
      Events = Matter.Events;

    // Créer le moteur
    var engine = Engine.create(),
      world = engine.world;

    // Créer le rendu
    var render = Render.create({
      element: document.body,
      canvas: document.getElementById("monCanvas"),
      engine: engine,
      options: {
        width: 850,
        height: 600,
        wireframes: false, // Désactiver le wireframe
        background: "#e5e5f700", // Arrière-plan transparent
      },
    });

    Render.run(render);

    // Créer le runner
    var runner = Runner.create();
    Runner.run(runner, engine);

    // Ajouter les murs
    Composite.add(world, [
      Bodies.rectangle(400, 0, 850, 50, {
        isStatic: true,
        render: { fillStyle: "#e5e5f700" },
      }), // Mur du haut
      Bodies.rectangle(400, 600, 800, 50, {
        isStatic: true,
        render: { fillStyle: "#e5e5f700" },
      }), // Mur du bas
      Bodies.rectangle(850, 300, 50, 600, {
        isStatic: true,
        render: { fillStyle: "#e5e5f700" },
      }), // Mur de droite
      Bodies.rectangle(0, 300, 50, 600, {
        isStatic: true,
        render: { fillStyle: "#e5e5f700" },
      }), // Mur de gauche
      Bodies.rectangle(548, 550, 10, 150, {
        isStatic: true,
        angle: Math.PI * -0.25,
        render: { fillStyle: "#e5e5f700" },
      }), // bowl
      Bodies.rectangle(766, 550, 10, 150, {
        isStatic: true,
        angle: Math.PI * 0.25,
        render: { fillStyle: "#e5e5f700" },
      }), // bowl
    ]);

    // Ajouter une rampe avec un angle
    Composite.add(world, [
      Bodies.rectangle(250, 200, 600, 20, {
        isStatic: true,
        angle: Math.PI * 0.03,
        render: { fillStyle: "#e5e5f700" },
      }),
      Bodies.circle(220, 400, 12, {
        friction: 0,
        restitution: 0.05,
        frictionAir: 0.01,
        render: {
          fillStyle: "#FFE86B",
        },
      }),
      Bodies.circle(270, 400, 12, {
        friction: 0,
        restitution: 0.05,
        frictionAir: 0.01,
        render: {
          fillStyle: "#FFE86B",
        },
      }),
      Bodies.circle(320, 400, 12, {
        friction: 0,
        restitution: 0.05,
        frictionAir: 0.01,
        render: {
          fillStyle: "#FFE86B",
        },
      }),
      Bodies.circle(380, 400, 12, {
        friction: 0,
        restitution: 0.05,
        frictionAir: 0.01,
        render: {
          fillStyle: "#FFE86B",
        },
      }),
    ]);

    // Créer et ajouter une pile de balles (stack) qui apparaissent en haut à gauche
    var boules = []; // Stocker les boules ici
    var maxBoules = 160; // Limite de 100 boules
    var milkInterval; // Pour stocker l'intervalle qui simule la chute du lait

    // Fonction pour ajouter une nouvelle boule simulant du lait qui tombe
    function ajouterBoule() {
      var balle = Bodies.circle(300, 80, Common.random(5, 10), {
        friction: 0,
        restitution: 0.05,
        frictionAir: 0.01,
        render: {
          fillStyle: "#FFFFFF",
          strokeStyle: "#FFFFFF",
          lineWidth: 1,
        },
      });

      // Ajouter la nouvelle boule au tableau et au monde
      balle.filter = "url(#gooey)";
      boules.push(balle);

      Composite.add(world, balle);

      // Si le nombre de boules dépasse la limite
      if (boules.length > maxBoules) {
        var oldBalle = boules.shift(); // Récupérer la première boule
        Composite.remove(world, oldBalle); // Supprimer la boule du monde
      }
    }

    var blocus = Bodies.rectangle(430, 170, 20, 120, {
      isStatic: true,
      angle: Math.PI * 0.03,
      render: { fillStyle: "#e5e5f700" },
    });

    Composite.add(world, blocus);

    // Ajouter des boules en continu (lait qui tombe)
    function startMilkFlow() {
      milkInterval = setInterval(ajouterBoule, 60); // Toutes les 100 ms
      Matter.Body.setPosition(blocus, {
        x: blocus.position.x,
        y: blocus.position.y + 100,
      });
    }

    // Arrêter l'écoulement du lait
    function stopMilkFlow() {
      clearInterval(milkInterval); // Arrêter l'intervalle
      Matter.Body.setPosition(blocus, {
        x: blocus.position.x,
        y: blocus.position.y - 100,
      });
    }

    // Sélectionner l'élément de la bouteille
    var bottle = document.querySelector(".bottleFront");

    // Ajouter des événements pour démarrer et arrêter le lait lors du hover
    bottle.addEventListener("mouseover", startMilkFlow); // Quand la souris entre
    bottle.addEventListener("mouseout", stopMilkFlow); // Quand la souris sort

    // Ajouter le contrôle de la souris
    var mouse = Mouse.create(render.canvas),
      mouseConstraint = MouseConstraint.create(engine, {
        mouse: mouse,
        constraint: {
          stiffness: 0.2,
          render: {
            visible: false,
          },
        },
      });

    Composite.add(world, mouseConstraint);

    // Synchroniser la souris avec le rendu
    render.mouse = mouse;

    // Adapter la vue pour voir l'ensemble de la scène
    Render.lookAt(render, {
      min: { x: 0, y: 0 },
      max: { x: 800, y: 600 },
    });

    moveElement();
  });

  function moveElement() {
    // Récupérer l'élément à déplacer par son id
    var element = document.getElementById("monCanvas");

    // Récupérer le conteneur cible où l'élément doit être déplacé
    var targetContainer = document.querySelector(".milk");

    // Déplacer l'élément en l'ajoutant dans le conteneur cible
    targetContainer.appendChild(element);
  }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/matter-js/0.19.0/matter.min.js"></script>

{/block}
