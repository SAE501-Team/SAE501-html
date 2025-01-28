import * as THREE from "three";
import { GLTFLoader } from "GLTFLoader";
import * as SkeletonUtils from "SkeletonUtils";


// Canvas
const canvas = document.querySelector("canvas.webgl");

// Scène
const scene = new THREE.Scene();


/**
 * Modèle
 */
const gltfLoader = new GLTFLoader();

let mixer = null;

// Chargement du modèle
gltfLoader.load(
    modelUrl,
  (gltf) => {
    gltf.scene.scale.set(1, 1, 1);
    gltf.scene.position.set(0, 0, 0);
    scene.add(gltf.scene);

    gltf.scene.rotation.y = THREE.MathUtils.degToRad(-110);
    gltf.scene.rotation.x = THREE.MathUtils.degToRad(10);

    // Initialiser l'animation
    mixer = new THREE.AnimationMixer(gltf.scene);
    gltf.animations.forEach((clip) => {
      const action = mixer.clipAction(clip);
      action.play();
    });
  }
);

/**
 * Lumières
 */
const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
directionalLight.position.set(5, 5, 5);
scene.add(directionalLight);

/**
 * Dimensions
 */
const sizes = {
  width: window.innerWidth,
  height: window.innerHeight,
};

window.addEventListener("resize", () => {
  sizes.width = window.innerWidth;
  sizes.height = window.innerHeight;

  camera.aspect = sizes.width / sizes.height;
  camera.updateProjectionMatrix();

  renderer.setSize(sizes.width, sizes.height);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
});

/**
 * Caméra
 */
const camera = new THREE.PerspectiveCamera(75, sizes.width / sizes.height);
camera.position.set(0, 1, 3);
scene.add(camera);

/**
 * Rendu
 */
const renderer = new THREE.WebGLRenderer({
  canvas: canvas,
  alpha: true, // Fond transparent
});
renderer.setSize(sizes.width, sizes.height);
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

/**
 * Animation
 */
const clock = new THREE.Clock();

const tick = () => {
  const delta = clock.getDelta();

  // Mettre à jour le mixer d'animation (si défini)
  if (mixer) {
    mixer.update(delta);
  }

  // Rendu
  renderer.render(scene, camera);

  // Appeler tick à nouveau à la prochaine image
  window.requestAnimationFrame(tick);
};

tick();
