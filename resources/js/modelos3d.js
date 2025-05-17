import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

document.addEventListener('DOMContentLoaded', () => {
    let modelo;
    const contenedor = document.getElementById('contenedor3d');
    const panelEjercicios = document.getElementById('panel-ejercicios');
    const musculoTitulo = document.getElementById('musculo-seleccionado');
    const listaEjercicios = document.getElementById('lista-ejercicios');

    // Configuración de Three.js
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, contenedor.clientWidth / contenedor.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    
    renderer.setSize(contenedor.clientWidth, contenedor.clientHeight);
    contenedor.appendChild(renderer.domElement);
    camera.position.set(0, 1.5, 5);

    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.15;
    controls.rotateSpeed = 0.4;
    controls.minDistance = 3;
    controls.maxDistance = 7;

    // Iluminación
    scene.add(new THREE.AmbientLight(0xffffff, 0.5));
    const dirLight = new THREE.DirectionalLight(0xffffff, 1);
    dirLight.position.set(5, 5, 5);
    scene.add(dirLight);

   // Modifica el array de partesCuerpo con estas posiciones y nombres corregidos
const partesCuerpo = [
      { nombre: 'Back', posicion: new THREE.Vector3(-1., 2, -1) },
        { nombre: 'Cardio', posicion: new THREE.Vector3(0, 0.5, 0.3) },
        { nombre: 'Chest', posicion: new THREE.Vector3(0, 1.5, 0.5) },
        { nombre: 'Lower legs', posicion: new THREE.Vector3(-1.5, -2.2, 0) },
        { nombre: 'Shoulders', posicion: new THREE.Vector3(1.3, 1.6, 0) },
        { nombre: 'Upper arms', posicion: new THREE.Vector3(-1.9, 0.7, 0) },
        { nombre: 'Upper legs', posicion: new THREE.Vector3(1, -0.8, 0) }
];


    // Crear elementos HTML para etiquetas
    partesCuerpo.forEach(parte => {
        const div = document.createElement('div');
        div.className = 'etiqueta-cuerpo';
        div.textContent = parte.nombre;
        div.dataset.parte = parte.nombre.toLowerCase();
        div.style.pointerEvents = 'auto';
        div.style.cursor = 'pointer';
        contenedor.appendChild(div);
        parte.elemento = div;

        // Evento click para cada etiqueta
        div.addEventListener('click', async () => {
            try {
                  listaEjercicios.innerHTML = '<div class="cargando">Cargando...</div>';
        
        const nombreApi = parte.nombre.toLowerCase().replace(/ /g, '-');
        
        const response = await fetch(`/ejercicios/${nombreApi}`);
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const ejercicios = await response.json();
        
        if (!ejercicios.length) {
            listaEjercicios.innerHTML = '<p>No hay ejercicios para esta categoría</p>';
            return;
        }   
            musculoTitulo.textContent = parte.nombre;


         listaEjercicios.innerHTML = ejercicios.slice(0, 15).map(ej => {
    let instructions = [];
    try {
        instructions = JSON.parse(ej.instructions.replace(/\\"/g, '"'));
    } catch (e) {
        instructions = ["Instrucciones no disponibles"];
    }
    
    return `
        <div class="ejercicio-card">
            <img src="${ej.gif}" alt="${ej.nombre}" loading="lazy">
            <h5>${ej.nombre}</h5>
            <ul class="instructions">
                ${instructions.map(inst => `<li>${inst}</li>`).join('')}
            </ul>
        </div>
    `;
}).join('');
            } catch (error) {
                console.error('Error:', error);
        listaEjercicios.innerHTML = `
            <p class="error">Error al cargar ejercicios</p>
            <small>${error.message}</small>
        `;
            }
        });
    });

function actualizarEtiquetas() {
    partesCuerpo.forEach(parte => {
        const vector = parte.posicion.clone();
        vector.project(camera);
        
        parte.elemento.style.display = vector.z > 1 ? 'none' : 'block';
        
        const targetX = (vector.x * 0.5 + 0.5) * contenedor.clientWidth;
        const targetY = (-vector.y * 0.5 + 0.5) * contenedor.clientHeight;
        
        // Interpolación suave
        const currentX = parseFloat(parte.elemento.style.left) || targetX;
        const currentY = parseFloat(parte.elemento.style.top) || targetY;
        
        parte.elemento.style.left = `${currentX + (targetX - currentX) * 0.15}px`;
        parte.elemento.style.top = `${currentY + (targetY - currentY) * 0.15}px`;
    });
}
    // Cargar modelo 3D
new GLTFLoader().load('/models/body_builder.glb', (gltf) => {
    modelo = gltf.scene;
    modelo.scale.set(3, 3, 3);
    modelo.position.set(0, 0, 0); 
    scene.add(modelo);
});
    // Animación
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        actualizarEtiquetas();
        renderer.render(scene, camera);
    }
    animate();

    // Responsive
    window.addEventListener('resize', () => {
        camera.aspect = contenedor.clientWidth / contenedor.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(contenedor.clientWidth, contenedor.clientHeight);
    });
});