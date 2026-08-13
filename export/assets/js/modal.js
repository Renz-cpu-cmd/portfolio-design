/**
 * Theme-consistent Modal System - Robust Version
 */
const CustomModal = {
    overlay: null,
    titleEl: null,
    contentEl: null,
    footerEl: null,
    
    init() {
        if (this.overlay) return;
        
        // Create elements
        this.overlay = document.createElement('div');
        this.overlay.className = 'modal-overlay';
        this.overlay.style.zIndex = '99999'; // Ensure it's on top
        
        this.overlay.innerHTML = `
            <div class="modal-container">
                <div class="modal-header">
                    <h3 class="modal-title">Notification</h3>
                </div>
                <div class="modal-content"></div>
                <div class="modal-footer"></div>
            </div>
        `;
        
        document.body.appendChild(this.overlay);
        
        // Cache references
        this.titleEl = this.overlay.querySelector('.modal-title');
        this.contentEl = this.overlay.querySelector('.modal-content');
        this.footerEl = this.overlay.querySelector('.modal-footer');
        
        // Close on overlay click (safety feature)
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.close();
            }
        });

        console.log("Custom Modal System Initialized");
    },
    
    alert(message, title = 'Notification') {
        console.log("Modal Alert:", title, message);
        return new Promise((resolve) => {
            try {
                this.init();
                
                this.titleEl.textContent = title;
                this.contentEl.textContent = message;
                this.footerEl.innerHTML = '';
                
                const btn = document.createElement('button');
                btn.className = 'modal-btn modal-btn-confirm';
                btn.textContent = 'OKAY';
                btn.onclick = () => {
                    this.close();
                    resolve();
                };
                
                this.footerEl.appendChild(btn);
                this.open();
            } catch (err) {
                console.error("Modal Alert Error:", err);
                window.originalAlert(message); // Fallback to native
                resolve();
            }
        });
    },
    
    confirm(message, title = 'Confirm Action') {
        console.log("Modal Confirm:", title, message);
        return new Promise((resolve) => {
            try {
                this.init();
                
                this.titleEl.textContent = title;
                this.contentEl.textContent = message;
                this.footerEl.innerHTML = '';
                
                const cancelBtn = document.createElement('button');
                cancelBtn.className = 'modal-btn modal-btn-cancel';
                cancelBtn.textContent = 'CANCEL';
                cancelBtn.onclick = () => {
                    this.close();
                    resolve(false);
                };
                
                const confirmBtn = document.createElement('button');
                confirmBtn.className = 'modal-btn modal-btn-confirm';
                confirmBtn.textContent = 'PROCEED';
                confirmBtn.onclick = () => {
                    this.close();
                    resolve(true);
                };
                
                this.footerEl.appendChild(cancelBtn);
                this.footerEl.appendChild(confirmBtn);
                this.open();
            } catch (err) {
                console.error("Modal Confirm Error:", err);
                const res = window.originalConfirm(message); // Fallback to native
                resolve(res);
            }
        });
    },
    
    open() {
        if (!this.overlay) return;
        this.overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Forced fallback: if modal doesn't show for some reason, allow scrolling back after 5 seconds
        setTimeout(() => {
            if (this.overlay.classList.contains('active') && this.overlay.offsetWidth === 0) {
                this.close();
                console.warn("Modal fallback triggered: Modal was 'active' but had no width (hidden).");
            }
        }, 5000);
    },
    
    close() {
        if (!this.overlay) return;
        this.overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
};

// Save original functions before overriding
window.originalAlert = window.alert;
window.originalConfirm = window.confirm;

// Global shortcuts
window.alert = (msg, title) => CustomModal.alert(msg, title);
window.confirm = (msg, title) => CustomModal.confirm(msg, title);
