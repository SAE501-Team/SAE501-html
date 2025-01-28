// Sélection de tous les éléments ayant la classe 'pane'


document.addEventListener("DOMContentLoaded", function () {
  const milk_bg = document.getElementById("milk_bg");
  const ctx = milk_bg.getContext("2d");

  // Ajuster la taille du canvas
  milk_bg.width = window.innerWidth;
  milk_bg.height = window.innerHeight;

  ctx.fillStyle = "white";
  ctx.strokeStyle = "white";

  // Classe Particule
  class Particle {
    constructor(effect) {
      this.effect = effect;
      this.radius = Math.random() * 15 + 5; // Taille ajustée pour ressembler à des gouttes
      this.x = Math.random() * this.effect.width;
      this.y = Math.random() * this.effect.height;
      this.vx = Math.random() * 1 - 0.5;
      this.vy = Math.random() * 1 - 0.5;
    }

    draw(context) {
      context.save();
      //context.filter = "blur(2px)"; // Ajouter un effet de flou
      context.beginPath();
      context.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
      context.fill();
      context.restore();
    }

    update() {
      this.x += this.vx;
      this.y += this.vy;

      // Rebonds sur les bords
      if (this.x - this.radius < 0 || this.x + this.radius > this.effect.width) {
        this.vx *= -1;
      }
      if (this.y - this.radius < 0 || this.y + this.radius > this.effect.height) {
        this.vy *= -1;
      }
    }
  }

  // Classe Effet
  class Effect {
    constructor(canvas, context) {
      this.canvas = canvas;
      this.context = context;
      this.width = this.canvas.width;
      this.height = this.canvas.height;
      this.particles = [];
      this.numberOfParticles = 70; // Réduire le nombre pour accentuer l'effet de gouttes

      this.createParticles();
    }

    createParticles() {
      for (let i = 0; i < this.numberOfParticles; i++) {
        this.particles.push(new Particle(this));
      }
    }

    handleParticles(context) {
      this.particles.forEach((particle) => {
        particle.draw(context);
        particle.update();
      });
    }
  }

  // Initialiser et animer l'effet
  const effect = new Effect(milk_bg, ctx);

  function animate() {
    ctx.clearRect(0, 0, milk_bg.width, milk_bg.height);
    effect.handleParticles(ctx);
    requestAnimationFrame(animate);
  }

  animate();
});



