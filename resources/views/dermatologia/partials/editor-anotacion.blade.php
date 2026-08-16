{{-- ═══════════════════════════════════════════════════════════
     EDITOR DE ANOTACIONES SOBRE FOTOS
     Incluir UNA vez por página con: @include('dermatologia.partials.editor-anotacion')

     <img src="{{ $foto->url }}" class="foto-anotable"
          data-foto-id="{{ $foto->id }}"
          data-foto-url="{{ $foto->url }}">
     ═══════════════════════════════════════════════════════════ --}}

<style>
    .foto-anotable {
        cursor: pointer;
    }

    .anot-toolbar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 10px;
    }

    .anot-toolbar .btn.tool-active {
        background: #0d47a1 !important;
        color: white !important;
        border-color: #0d47a1 !important;
    }

    .anot-canvas-wrap {
        background: #1c1c1c;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        touch-action: none;
        position: relative;
        cursor: grab;
    }

    .anot-canvas-wrap.panning {
        cursor: grabbing;
    }

    .anot-hint {
        font-size: 12px;
        color: #6c8bb5;
        margin-top: 8px;
        text-align: center;
        min-height: 16px;
    }

    /* FIX: Evitar scroll al editar texto */
    #modalAnotador .modal-body {
        overflow-y: auto;
        position: relative;
    }

    #modalAnotador .modal-body .anot-canvas-wrap {
        position: relative;
    }

    /* El textarea de fabric debe permanecer dentro del canvas wrap */
    .anot-canvas-wrap textarea {
        position: absolute !important;
        z-index: 1000 !important;
        max-width: 300px !important;
        min-height: 30px !important;
        top: 0 !important;
        left: 0 !important;
    }

    /* Clase para bloquear el scroll */
    .anot-scroll-lock {
        overflow: hidden !important;
        height: 100% !important;
    }

    /* Indicador de zoom */
    .anot-zoom-indicator {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        pointer-events: none;
        z-index: 100;
    }

    /* Toast de notificación */
    .anot-toast {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 99999;
        font-size: 14px;
        display: none;
        max-width: 90%;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .anot-toast.success {
        background: rgba(40, 167, 69, 0.9);
    }

    .anot-toast.error {
        background: rgba(220, 53, 69, 0.9);
    }

    .anot-toast.warning {
        background: rgba(255, 193, 7, 0.9);
        color: #000;
    }

    .anot-toast.show {
        display: block;
        animation: anotToastAnim 0.3s ease;
    }

    @keyframes anotToastAnim {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }
</style>

<div class="modal fade" id="modalAnotador" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Anotar foto</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="anotModalBody">
                <div class="anot-toolbar">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnHerrCirculo"
                        onclick="anotArmarHerramienta('circulo')">⭕ Círculo</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnHerrFlecha"
                        onclick="anotArmarHerramienta('flecha')">➜ Flecha</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnHerrTexto"
                        onclick="anotArmarHerramienta('texto')">🔤 Texto</button>
                    <input type="color" id="anotColor" value="#ff0000" class="form-control form-control-color"
                        title="Color">

                    <span class="border-start ps-2 ms-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnPan"
                            onclick="anotActivarPan()">✋ Mover</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="anotZoom(1.2)">➕</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="anotZoom(0.8)">➖</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="anotResetZoom()">100%</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="anotAjustarZoom()">🔄
                            Ajustar</button>
                    </span>

                    <span class="border-start ps-2 ms-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="anotEliminarSeleccion()">🗑️</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="anotLimpiarTodo()">Limpiar todo</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="anotDesarmarHerramienta()">🔓 Desactivar</button>
                    </span>
                </div>
                <div class="anot-canvas-wrap" id="anotCanvasWrap">
                    <canvas id="anotCanvas"></canvas>
                    <div class="anot-zoom-indicator" id="anotZoomIndicator">100%</div>
                </div>
                <div class="anot-hint" id="anotHint">Elige una herramienta y dibuja sobre la imagen · Rueda del mouse =
                    zoom · Click derecho, Espacio+arrastre o botón Mover para arrastrar · Delete/Backspace borra lo
                    seleccionado</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="anotGuardar()">
                    <span id="anotGuardarTexto">Guardar anotación</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast para notificaciones -->
<div class="anot-toast" id="anotToast"></div>

@once
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script>
        let anotCanvas = null;
        let anotFotoId = null;
        let anotZoomActual = 1;

        // Herramienta activa: null (selección normal) | 'circulo' | 'flecha' | 'texto' | 'pan'
        let anotHerramienta = null;
        let anotPuntoInicio = null;
        let anotFormaTemp = null;
        let anotEstaDibujando = false;
        let anotScrollTopGuardado = 0;
        let anotEditandoTexto = false;
        let anotPanActivado = false;
        let anotUltimoPuntoPan = null;
        let anotTeclaEspacioPresionada = false;

        // ── Toast de notificaciones ──
        function anotMostrarToast(mensaje, tipo = 'info', duracion = 3000) {
            const toast = document.getElementById('anotToast');
            toast.textContent = mensaje;
            toast.className = 'anot-toast ' + tipo + ' show';
            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.classList.remove('show');
            }, duracion);
        }

        // ── Obtener CSRF token de manera segura ──
        function anotObtenerCsrfToken() {
            // Intentar obtener del meta tag
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                return metaToken.getAttribute('content');
            }

            // Intentar obtener de la cookie
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                const [name, value] = cookie.trim().split('=');
                if (name === 'XSRF-TOKEN' || name === 'csrf_token') {
                    return decodeURIComponent(value);
                }
            }

            // Si no hay token, mostrar error
            console.warn('No se encontró CSRF token');
            return null;
        }

        // ── Refrescar CSRF token ──
        function anotRefrescarCsrf() {
            return fetch('/refresh-csrf', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.token) {
                        // Actualizar meta tag
                        const metaToken = document.querySelector('meta[name="csrf-token"]');
                        if (metaToken) {
                            metaToken.setAttribute('content', data.token);
                        }
                        return data.token;
                    }
                    throw new Error('No se pudo renovar el token CSRF');
                });
        }

        document.addEventListener('click', function(e) {
            const img = e.target.closest('.foto-anotable');
            if (!img) return;
            abrirEditorAnotacion(img);
        });

        function abrirEditorAnotacion(imgEl) {
            anotFotoId = imgEl.dataset.fotoId;
            const urlOriginal = imgEl.dataset.fotoUrl;

            anotHerramienta = null;
            anotPanActivado = false;

            new bootstrap.Modal(document.getElementById('modalAnotador')).show();

            document.getElementById('modalAnotador').addEventListener('shown.bs.modal', function iniciar() {
                this.removeEventListener('shown.bs.modal', iniciar);

                if (anotCanvas) {
                    anotCanvas.dispose();
                }

                const maxWidth = Math.min(900, window.innerWidth - 80);

                fabric.Image.fromURL(urlOriginal, function(img) {
                    const escalaImg = maxWidth / img.width;
                    const alto = img.height * escalaImg;

                    anotCanvas = new fabric.Canvas('anotCanvas', {
                        width: maxWidth,
                        height: alto,
                        selection: true,
                    });

                    img.set({
                        scaleX: escalaImg,
                        scaleY: escalaImg,
                        selectable: false,
                        evented: false
                    });
                    anotCanvas.setBackgroundImage(img, anotCanvas.renderAll.bind(anotCanvas));

                    anotZoomActual = 1;
                    actualizarIndicadorZoom();
                    habilitarZoomRueda();
                    habilitarFixTextoModal();
                    habilitarDibujoHerramientas();
                    habilitarPan();
                    habilitarPanConTeclas();
                }, {
                    crossOrigin: 'anonymous'
                });
            }, {
                once: true
            });
        }

        // ── FIX DEFINITIVO para el scroll al editar texto ──
        function habilitarFixTextoModal() {
            anotCanvas.on('text:editing:entered', function() {
                anotEditandoTexto = true;

                const modal = document.getElementById('modalAnotador');
                const modalBody = modal.querySelector('.modal-body');

                if (modalBody) {
                    anotScrollTopGuardado = modalBody.scrollTop;
                    modalBody.classList.add('anot-scroll-lock');
                    modalBody.style.overflow = 'hidden';
                }

                const obj = anotCanvas.getActiveObject();
                if (obj && obj.hiddenTextarea) {
                    const canvasWrap = document.getElementById('anotCanvasWrap');
                    canvasWrap.appendChild(obj.hiddenTextarea);

                    const zoom = anotCanvas.getZoom();
                    const canvasEl = anotCanvas.getElement();
                    const rect = canvasEl.getBoundingClientRect();

                    const left = (obj.left * zoom) + (rect.left - canvasEl.offsetLeft);
                    const top = (obj.top * zoom) + (rect.top - canvasEl.offsetTop);

                    obj.hiddenTextarea.style.position = 'fixed';
                    obj.hiddenTextarea.style.left = left + 'px';
                    obj.hiddenTextarea.style.top = top + 'px';
                    obj.hiddenTextarea.style.zIndex = '9999';
                    obj.hiddenTextarea.style.width = '200px';
                    obj.hiddenTextarea.style.height = '30px';

                    setTimeout(() => {
                        try {
                            obj.hiddenTextarea.focus({
                                preventScroll: true
                            });
                        } catch (e) {
                            obj.hiddenTextarea.focus();
                        }
                        if (modalBody) {
                            modalBody.scrollTop = anotScrollTopGuardado;
                            modalBody.style.overflow = 'hidden';
                        }
                    }, 10);
                }
            });

            anotCanvas.on('text:editing:exited', function() {
                anotEditandoTexto = false;
                const modal = document.getElementById('modalAnotador');
                const modalBody = modal.querySelector('.modal-body');
                if (modalBody) {
                    modalBody.scrollTop = anotScrollTopGuardado;
                    modalBody.classList.remove('anot-scroll-lock');
                    modalBody.style.overflow = '';
                }
            });
        }

        function habilitarZoomRueda() {
            anotCanvas.on('mouse:wheel', function(opt) {
                const delta = opt.e.deltaY;
                let zoom = anotCanvas.getZoom();
                zoom *= 0.999 ** delta;
                zoom = Math.min(Math.max(zoom, 0.3), 5);
                anotCanvas.zoomToPoint({
                    x: opt.e.offsetX,
                    y: opt.e.offsetY
                }, zoom);
                anotZoomActual = zoom;
                actualizarIndicadorZoom();
                opt.e.preventDefault();
                opt.e.stopPropagation();
            });
        }

        function actualizarIndicadorZoom() {
            const indicator = document.getElementById('anotZoomIndicator');
            if (indicator) {
                indicator.textContent = Math.round(anotZoomActual * 100) + '%';
            }
        }

        // ═══════════════════════════════════════════════════════════
        // PAN (DESPLAZAMIENTO)
        // ═══════════════════════════════════════════════════════════

        function habilitarPan() {
            const canvasWrap = document.getElementById('anotCanvasWrap');

            anotCanvas.on('mouse:down', function(opt) {
                if (opt.e.button === 2 || anotPanActivado || anotTeclaEspacioPresionada) {
                    anotUltimoPuntoPan = anotCanvas.getPointer(opt.e);
                    canvasWrap.classList.add('panning');
                    anotCanvas.selection = false;
                    anotCanvas.defaultCursor = 'grabbing';
                    opt.e.preventDefault();
                    return;
                }
            });

            anotCanvas.on('mouse:move', function(opt) {
                if (anotUltimoPuntoPan) {
                    const p = anotCanvas.getPointer(opt.e);
                    const dx = p.x - anotUltimoPuntoPan.x;
                    const dy = p.y - anotUltimoPuntoPan.y;
                    anotCanvas.relativePan({
                        x: dx,
                        y: dy
                    });
                    anotUltimoPuntoPan = p;
                    opt.e.preventDefault();
                }
            });

            anotCanvas.on('mouse:up', function(opt) {
                if (anotUltimoPuntoPan) {
                    anotUltimoPuntoPan = null;
                    canvasWrap.classList.remove('panning');
                    anotCanvas.selection = true;
                    anotCanvas.defaultCursor = 'default';
                    if (!anotPanActivado && !anotTeclaEspacioPresionada) {
                        anotCanvas.defaultCursor = anotHerramienta ? 'crosshair' : 'default';
                    }
                }
            });

            anotCanvas.wrapperEl.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });
        }

        function habilitarPanConTeclas() {
            document.addEventListener('keydown', function(e) {
                if (e.key === ' ' || e.key === 'Space') {
                    const modal = document.getElementById('modalAnotador');
                    if (modal && modal.classList.contains('show')) {
                        if (!anotTeclaEspacioPresionada && !anotEditandoTexto) {
                            anotTeclaEspacioPresionada = true;
                            document.getElementById('anotCanvas').style.cursor = 'grab';
                            document.getElementById('anotHint').textContent =
                                'Modo Mover (Espacio) · Arrastra para desplazarte por la imagen';
                            anotCanvas.defaultCursor = 'grab';
                            e.preventDefault();
                        }
                    }
                }
                if (e.key === 'Control' || e.key === 'Meta') {
                    const modal = document.getElementById('modalAnotador');
                    if (modal && modal.classList.contains('show')) {
                        if (!anotPanActivado && !anotEditandoTexto) {
                            anotTeclaEspacioPresionada = true;
                            document.getElementById('anotCanvas').style.cursor = 'grab';
                            anotCanvas.defaultCursor = 'grab';
                        }
                    }
                }
            });

            document.addEventListener('keyup', function(e) {
                if (e.key === ' ' || e.key === 'Space' || e.key === 'Control' || e.key === 'Meta') {
                    if (anotTeclaEspacioPresionada) {
                        anotTeclaEspacioPresionada = false;
                        if (!anotPanActivado) {
                            document.getElementById('anotCanvas').style.cursor = anotHerramienta ? 'crosshair' :
                                'default';
                            anotCanvas.defaultCursor = anotHerramienta ? 'crosshair' : 'default';
                            document.getElementById('anotHint').textContent =
                                'Elige una herramienta y dibuja sobre la imagen · Rueda del mouse = zoom · Click derecho, Espacio+arrastre o botón Mover para arrastrar · Delete/Backspace borra lo seleccionado';
                        }
                    }
                }
            });
        }

        function anotActivarPan() {
            anotPanActivado = !anotPanActivado;
            const btnPan = document.getElementById('btnPan');

            if (anotPanActivado) {
                btnPan.classList.add('tool-active');
                document.getElementById('anotCanvas').style.cursor = 'grab';
                document.getElementById('anotHint').textContent =
                    'Modo Mover activado · Arrastra para desplazarte por la imagen';
                anotDesarmarHerramienta();
            } else {
                btnPan.classList.remove('tool-active');
                document.getElementById('anotCanvas').style.cursor = 'default';
                document.getElementById('anotHint').textContent =
                    'Elige una herramienta y dibuja sobre la imagen · Rueda del mouse = zoom · Click derecho, Espacio+arrastre o botón Mover para arrastrar · Delete/Backspace borra lo seleccionado';
            }
        }

        function anotZoom(factor) {
            if (!anotCanvas) return;
            anotZoomActual = Math.min(Math.max(anotZoomActual * factor, 0.3), 5);
            anotCanvas.setZoom(anotZoomActual);
            actualizarIndicadorZoom();
        }

        function anotResetZoom() {
            if (!anotCanvas) return;
            anotZoomActual = 1;
            anotCanvas.setZoom(1);
            anotCanvas.absolutePan({
                x: 0,
                y: 0
            });
            actualizarIndicadorZoom();
        }

        function anotAjustarZoom() {
            if (!anotCanvas) return;
            const canvasWrap = document.getElementById('anotCanvasWrap');
            const canvasWidth = anotCanvas.getWidth();
            const canvasHeight = anotCanvas.getHeight();
            const wrapWidth = canvasWrap.clientWidth;
            const wrapHeight = canvasWrap.clientHeight;

            const zoomX = wrapWidth / canvasWidth;
            const zoomY = wrapHeight / canvasHeight;
            const zoom = Math.min(zoomX, zoomY, 1);

            anotZoomActual = Math.max(zoom, 0.3);
            anotCanvas.setZoom(anotZoomActual);
            anotCanvas.absolutePan({
                x: 0,
                y: 0
            });
            actualizarIndicadorZoom();
        }

        function anotColorActual() {
            return document.getElementById('anotColor').value;
        }

        // ═══════════════════════════════════════════════════════════
        // SELECCIÓN DE HERRAMIENTA
        // ═══════════════════════════════════════════════════════════

        function anotArmarHerramienta(tipo) {
            if (anotPanActivado) {
                anotActivarPan();
            }

            if (anotHerramienta === tipo) {
                anotDesarmarHerramienta();
                return;
            }

            anotHerramienta = tipo;
            document.querySelectorAll('.anot-toolbar .btn').forEach(b => b.classList.remove('tool-active'));
            const mapaBtn = {
                circulo: 'btnHerrCirculo',
                flecha: 'btnHerrFlecha',
                texto: 'btnHerrTexto'
            };
            if (anotHerramienta && mapaBtn[anotHerramienta]) {
                document.getElementById(mapaBtn[anotHerramienta]).classList.add('tool-active');
            }

            const canvasEl = document.getElementById('anotCanvas');
            canvasEl.style.cursor = anotHerramienta ? 'crosshair' : 'default';

            const mensajes = {
                circulo: 'Haz clic y arrastra sobre la imagen para dibujar el círculo (el centro es donde hiciste clic).',
                flecha: 'Haz clic y arrastra para trazar la flecha.',
                texto: 'Haz clic en la imagen donde quieras colocar el texto.',
            };
            document.getElementById('anotHint').textContent = anotHerramienta ?
                mensajes[anotHerramienta] :
                'Elige una herramienta y dibuja sobre la imagen · Rueda del mouse = zoom · Click derecho, Espacio+arrastre o botón Mover para arrastrar · Delete/Backspace borra lo seleccionado';
        }

        function anotDesarmarHerramienta() {
            anotHerramienta = null;
            anotEstaDibujando = false;
            document.querySelectorAll('.anot-toolbar .btn').forEach(b => b.classList.remove('tool-active'));
            document.getElementById('anotCanvas').style.cursor = 'default';
            document.getElementById('anotHint').textContent =
                'Elige una herramienta y dibuja sobre la imagen · Rueda del mouse = zoom · Click derecho, Espacio+arrastre o botón Mover para arrastrar · Delete/Backspace borra lo seleccionado';

            if (anotFormaTemp) {
                anotCanvas.remove(anotFormaTemp);
                anotFormaTemp = null;
            }
            anotPuntoInicio = null;
        }

        // ═══════════════════════════════════════════════════════════
        // DIBUJO INTERACTIVO
        // ═══════════════════════════════════════════════════════════

        function habilitarDibujoHerramientas() {
            anotCanvas.on('mouse:down', function(opt) {
                if (opt.e.button === 2 || anotPanActivado || anotTeclaEspacioPresionada) return;
                if (!anotHerramienta) return;

                if (opt.target) {
                    if (anotHerramienta === 'texto' && opt.target.type === 'i-text') {
                        anotCanvas.setActiveObject(opt.target);
                        anotCanvas.renderAll();

                        const modal = document.getElementById('modalAnotador');
                        const modalBody = modal.querySelector('.modal-body');
                        if (modalBody) {
                            anotScrollTopGuardado = modalBody.scrollTop;
                        }

                        setTimeout(() => {
                            if (opt.target.enterEditing) {
                                opt.target.enterEditing();
                                setTimeout(() => {
                                    if (modalBody) {
                                        modalBody.scrollTop = anotScrollTopGuardado;
                                    }
                                }, 20);
                            }
                        }, 50);
                        return;
                    }
                    return;
                }

                const p = anotCanvas.getPointer(opt.e);
                anotPuntoInicio = {
                    x: p.x,
                    y: p.y
                };
                const color = anotColorActual();

                if (anotHerramienta === 'texto') {
                    const texto = new fabric.IText('Escribe aquí', {
                        left: p.x,
                        top: p.y,
                        fontSize: 22,
                        fill: color,
                        fontWeight: 'bold',
                    });
                    anotCanvas.add(texto);
                    anotCanvas.setActiveObject(texto);
                    anotCanvas.renderAll();

                    const modal = document.getElementById('modalAnotador');
                    const modalBody = modal.querySelector('.modal-body');
                    if (modalBody) {
                        anotScrollTopGuardado = modalBody.scrollTop;
                    }

                    setTimeout(() => {
                        texto.enterEditing();
                        setTimeout(() => {
                            if (modalBody) {
                                modalBody.scrollTop = anotScrollTopGuardado;
                            }
                        }, 20);
                    }, 50);
                    return;
                }

                if (anotHerramienta === 'circulo') {
                    anotFormaTemp = new fabric.Circle({
                        left: p.x,
                        top: p.y,
                        radius: 1,
                        originX: 'center',
                        originY: 'center',
                        fill: 'transparent',
                        stroke: color,
                        strokeWidth: 4,
                        selectable: false,
                        evented: false,
                    });
                    anotCanvas.add(anotFormaTemp);
                    anotEstaDibujando = true;
                } else if (anotHerramienta === 'flecha') {
                    anotFormaTemp = new fabric.Line([p.x, p.y, p.x, p.y], {
                        stroke: color,
                        strokeWidth: 4,
                        selectable: false,
                        evented: false,
                    });
                    anotCanvas.add(anotFormaTemp);
                    anotEstaDibujando = true;
                }
            });

            anotCanvas.on('mouse:move', function(opt) {
                if (!anotFormaTemp || !anotPuntoInicio || !anotEstaDibujando) return;
                const p = anotCanvas.getPointer(opt.e);

                if (anotHerramienta === 'circulo') {
                    const dx = p.x - anotPuntoInicio.x;
                    const dy = p.y - anotPuntoInicio.y;
                    const radio = Math.sqrt(dx * dx + dy * dy);
                    anotFormaTemp.set({
                        radius: radio
                    });
                } else if (anotHerramienta === 'flecha') {
                    anotFormaTemp.set({
                        x2: p.x,
                        y2: p.y
                    });
                }
                anotCanvas.renderAll();
            });

            anotCanvas.on('mouse:up', function(opt) {
                if (!anotFormaTemp || !anotPuntoInicio || !anotEstaDibujando) return;

                const p = anotCanvas.getPointer(opt.e);
                const dx = p.x - anotPuntoInicio.x;
                const dy = p.y - anotPuntoInicio.y;
                const distancia = Math.sqrt(dx * dx + dy * dy);

                anotCanvas.remove(anotFormaTemp);
                const herramientaUsada = anotHerramienta;
                anotFormaTemp = null;
                anotEstaDibujando = false;

                if (distancia < 5) {
                    return;
                }

                const color = anotColorActual();

                if (herramientaUsada === 'circulo') {
                    const circuloFinal = new fabric.Circle({
                        left: anotPuntoInicio.x,
                        top: anotPuntoInicio.y,
                        radius: distancia,
                        originX: 'center',
                        originY: 'center',
                        fill: 'transparent',
                        stroke: color,
                        strokeWidth: 4,
                    });
                    anotCanvas.add(circuloFinal);
                    anotCanvas.renderAll();
                } else if (herramientaUsada === 'flecha') {
                    const angulo = Math.atan2(dy, dx) * 180 / Math.PI;
                    const linea = new fabric.Line([anotPuntoInicio.x, anotPuntoInicio.y, p.x, p.y], {
                        stroke: color,
                        strokeWidth: 4
                    });
                    const punta = new fabric.Triangle({
                        left: p.x,
                        top: p.y,
                        originX: 'center',
                        originY: 'center',
                        angle: angulo + 90,
                        width: 16,
                        height: 18,
                        fill: color,
                    });
                    const grupo = new fabric.Group([linea, punta]);
                    anotCanvas.add(grupo);
                    anotCanvas.renderAll();
                }
            });
        }

        // ═══════════════════════════════════════════════════════════
        // BORRAR CON DELETE / BACKSPACE
        // ═══════════════════════════════════════════════════════════

        document.addEventListener('keydown', function(e) {
            if (!anotCanvas) return;
            if (!document.getElementById('modalAnotador').classList.contains('show')) return;
            if (e.key !== 'Delete' && e.key !== 'Backspace') return;

            const obj = anotCanvas.getActiveObject();
            if (!obj) return;
            if (obj.isEditing) return;

            e.preventDefault();
            anotEliminarSeleccion();
        });

        function anotEliminarSeleccion() {
            if (!anotCanvas) return;
            anotCanvas.getActiveObjects().forEach(obj => anotCanvas.remove(obj));
            anotCanvas.discardActiveObject();
            anotCanvas.renderAll();
        }

        function anotLimpiarTodo() {
            if (!anotCanvas) return;
            anotCanvas.getObjects().forEach(obj => anotCanvas.remove(obj));
            anotCanvas.renderAll();
        }

        // ═══════════════════════════════════════════════════════════════
        // GUARDAR - VERSIÓN CORREGIDA
        // ═══════════════════════════════════════════════════════════════

        function anotGuardar() {
            if (!anotCanvas || !anotFotoId) {
                anotMostrarToast('No hay canvas o foto seleccionada.', 'error');
                return;
            }

            const boton = document.getElementById('anotGuardarTexto');
            const botonOriginal = boton.textContent;
            boton.textContent = 'Guardando...';
            boton.disabled = true;

            const zoomPrevio = anotCanvas.getZoom();
            const panPrevio = anotCanvas.viewportTransform ? [...anotCanvas.viewportTransform] : [1, 0, 0, 1, 0, 0];

            try {
                // Resetear zoom y pan para capturar la imagen completa
                anotCanvas.setZoom(1);
                anotCanvas.absolutePan({
                    x: 0,
                    y: 0
                });
                anotCanvas.renderAll();

                let dataUrl = null;
                try {
                    dataUrl = anotCanvas.toDataURL({
                        format: 'png',
                        quality: 0.9
                    });
                } catch (e) {
                    console.error('Error al generar la imagen:', e);
                    boton.textContent = botonOriginal;
                    boton.disabled = false;
                    anotMostrarToast('Error al generar la imagen de la anotación.', 'error');
                    anotCanvas.setZoom(zoomPrevio);
                    anotCanvas.viewportTransform = panPrevio;
                    anotCanvas.renderAll();
                    return;
                }

                // Restaurar zoom y pan
                anotCanvas.setZoom(zoomPrevio);
                anotCanvas.viewportTransform = panPrevio;
                anotCanvas.renderAll();

                if (!dataUrl) {
                    boton.textContent = botonOriginal;
                    boton.disabled = false;
                    anotMostrarToast('No se pudo generar la imagen.', 'error');
                    return;
                }

                const anotacionesJson = JSON.stringify(anotCanvas.toJSON());
                const formData = new FormData();
                formData.append('imagen_base64', dataUrl);
                formData.append('anotaciones_json', anotacionesJson);

                // Obtener token CSRF del meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    boton.textContent = botonOriginal;
                    boton.disabled = false;
                    anotMostrarToast('Error de seguridad: No se encontró el token CSRF. Recarga la página.', 'error', 5000);
                    return;
                }

                console.log('Enviando petición a:', `/procedimiento-fotos/${anotFotoId}/anotar`);

                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 60000);

                // Función para realizar la petición
                function realizarPeticion(token) {
                    const newFormData = new FormData();
                    newFormData.append('imagen_base64', dataUrl);
                    newFormData.append('anotaciones_json', anotacionesJson);

                    return fetch(`/procedimiento-fotos/${anotFotoId}/anotar`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: newFormData,
                        signal: controller.signal,
                    });
                }

                // Primera petición
                realizarPeticion(csrfToken)
                    .then(response => {
                        clearTimeout(timeoutId);

                        console.log('Respuesta del servidor:', response.status, response.statusText);

                        // Si es error 419, obtener un nuevo token de la cookie y reintentar
                        if (response.status === 419) {
                            anotMostrarToast('El token de seguridad expiró. Reintentando...', 'warning', 3000);

                            // Obtener nuevo token de la cookie XSRF-TOKEN
                            const cookies = document.cookie.split(';');
                            let nuevoToken = null;
                            for (let cookie of cookies) {
                                const [name, value] = cookie.trim().split('=');
                                if (name === 'XSRF-TOKEN') {
                                    nuevoToken = decodeURIComponent(value);
                                    break;
                                }
                            }

                            if (!nuevoToken) {
                                nuevoToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            }

                            if (!nuevoToken) {
                                anotMostrarToast('No se pudo renovar el token. Recargando página...', 'error', 3000);
                                setTimeout(() => location.reload(), 2000);
                                throw new Error('No se pudo obtener un nuevo token CSRF');
                            }

                            const metaToken = document.querySelector('meta[name="csrf-token"]');
                            if (metaToken) {
                                metaToken.setAttribute('content', nuevoToken);
                            }

                            return realizarPeticion(nuevoToken);
                        }

                        if (!response.ok) {
                            // Intentar leer el mensaje de error del servidor
                            return response.text().then(text => {
                                let errorMsg = `Error HTTP ${response.status}`;
                                try {
                                    const json = JSON.parse(text);
                                    if (json.error) errorMsg = json.error;
                                    if (json.message) errorMsg = json.message;
                                } catch (e) {
                                    // Si no es JSON, usar el texto
                                    if (text) errorMsg = text.substring(0, 200);
                                }
                                throw new Error(errorMsg);
                            });
                        }

                        // Parsear JSON de la respuesta
                        return response.json().then(data => {
                            console.log('Datos recibidos:', data);
                            return data;
                        });
                    })
                    .then(data => {
                        clearTimeout(timeoutId);

                        // Verificar si la respuesta es exitosa
                        if (data && data.success === true) {
                            anotMostrarToast('✅ Anotación guardada correctamente.', 'success', 2000);
                            boton.textContent = '✅ Guardado';
                            setTimeout(() => {
                                location.reload();
                            }, 800);
                        } else {
                            // Si no hay success: true, mostrar el error
                            const errorMsg = data?.error || data?.message || 'Error desconocido al guardar.';
                            console.error('Error en respuesta:', data);
                            throw new Error(errorMsg);
                        }
                    })
                    .catch(error => {
                        clearTimeout(timeoutId);
                        boton.textContent = botonOriginal;
                        boton.disabled = false;

                        let mensaje = 'Error al guardar la anotación.';
                        if (error.name === 'AbortError') {
                            mensaje = 'La petición ha tardado demasiado. Intenta nuevamente.';
                        } else if (error.message) {
                            mensaje = `Error: ${error.message}`;
                        }
                        console.error('Error al guardar:', error);
                        anotMostrarToast(mensaje, 'error', 5000);
                    });

            } catch (error) {
                console.error('Error en el proceso de guardado:', error);
                boton.textContent = botonOriginal;
                boton.disabled = false;
                anotCanvas.setZoom(zoomPrevio);
                anotCanvas.viewportTransform = panPrevio;
                anotCanvas.renderAll();
                anotMostrarToast('Error al procesar la anotación. Intenta nuevamente.', 'error');
            }
        }
    </script>
@endonce
