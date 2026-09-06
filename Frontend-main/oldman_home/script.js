document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');

    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    // Optional: Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 992) {
            if (!sidebar.contains(e.target) && !mobileMenuBtn.contains(e.target) && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        }
    });

    // Modal Logic
    const openModalBtn = document.getElementById('open-new-request-modal');
    const editAndRepostBtn = document.querySelector('.edit-and-repost');
    const prevBtn = document.getElementById('modal-prev-btn');
    const nextBtn = document.getElementById('modal-next-btn');
    const modalOverlay = document.getElementById('new-request-modal');
    const modalTitle = document.querySelector('.modal-title');
    const modalOptions = document.querySelectorAll('.modal-option');
    
    // Steps
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const stepper1 = document.getElementById('stepper-1');
    const stepper2 = document.getElementById('stepper-2');
    const stepper3 = document.getElementById('stepper-3');

    let currentStep = 1;
    let selectedOption = null;
    let isEditAndRepostMode = false;

    // My requests tabs
    const requestTabs = document.querySelectorAll('.tab-btn[data-tab]');
    const activeRequests = document.getElementById('active-requests');
    const requestsEmptyState = document.querySelector('.requests-empty-state');
    const actionRequiredRequests = document.getElementById('action-required-requests');
    const completedRequests = document.getElementById('completed-requests');
    const cancelledRequests = document.getElementById('cancelled-requests');

    requestTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            requestTabs.forEach((item) => item.classList.toggle('active', item === tab));

            const selectedTab = tab.dataset.tab;
            const showAllRequests = selectedTab === 'all';
            const showActiveRequests = selectedTab === 'active';
            const showActionRequired = selectedTab === 'action-required';
            const showCompleted = selectedTab === 'completed';
            const showCancelled = selectedTab === 'cancelled';
            if (activeRequests) activeRequests.hidden = !showActiveRequests;
            if (actionRequiredRequests) actionRequiredRequests.hidden = !showActionRequired;
            if (completedRequests) completedRequests.hidden = !showCompleted;
            if (cancelledRequests) cancelledRequests.hidden = !showCancelled;
            if (requestsEmptyState) requestsEmptyState.hidden = !showAllRequests;
        });
    });

    const actionCard = document.querySelector('.action-required-card');
    const confirmRequest = document.querySelector('.confirm-request');
    const cancelRequest = document.querySelector('.cancel-request');

    if (confirmRequest && actionCard) {
        confirmRequest.addEventListener('click', () => {
            actionCard.innerHTML = `
                <div class="action-card-accent" style="background:#5a8027"></div>
                <div class="action-alert" style="background:#e7f2d7;color:#45671c">
                    <i class="fa-solid fa-circle-check"></i>
                    <div><strong>تم تأكيد الموعد بنجاح</strong><p>سيصلك إشعار عند انطلاق مقدم الخدمة.</p></div>
                </div>`;
        });
    }

    if (cancelRequest && actionCard) {
        cancelRequest.addEventListener('click', () => {
            actionCard.innerHTML = `
                <div class="action-card-accent" style="background:#8a8a80"></div>
                <div class="action-alert" style="background:#f1f1ef;color:#666">
                    <i class="fa-solid fa-circle-info"></i>
                    <div><strong>تم إلغاء الطلب</strong><p>يمكنك إرسال طلب مساعدة جديد في أي وقت.</p></div>
                </div>`;
        });
    }

    function resetModal() {
        isEditAndRepostMode = false;
        currentStep = 1;
        step1.style.display = 'block';
        step2.style.display = 'none';
        step3.style.display = 'none';
        stepper2.classList.remove('active');
        stepper3.classList.remove('active');
        prevBtn.textContent = 'الغاء';
        prevBtn.className = 'modal-btn cancel-btn';
        nextBtn.textContent = 'التالي';
        if (modalTitle) modalTitle.textContent = 'طلب مساعدة جديد';
        modalOptions.forEach(opt => opt.classList.remove('selected'));
        selectedOption = null;
        
        // Clear inputs
        const reqDesc = document.getElementById('req-desc');
        const reqTime = document.getElementById('req-time');
        const reqAddress = document.getElementById('req-address');
        if (reqDesc) reqDesc.value = '';
        if (reqTime) reqTime.value = '';
        if (reqAddress) reqAddress.value = '';
    }

    if (openModalBtn && modalOverlay) {
        openModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            resetModal();
            modalOverlay.classList.add('show');
        });
    }

    // Edit and repost uses the request-details step directly, with its own actions.
    if (editAndRepostBtn && modalOverlay) {
        editAndRepostBtn.addEventListener('click', () => {
            resetModal();
            isEditAndRepostMode = true;
            step1.style.display = 'none';
            step2.style.display = 'block';
            stepper2.classList.add('active');
            prevBtn.textContent = 'تراجع';
            nextBtn.textContent = 'إعادة نشر الطلب';
            currentStep = 2;
            modalOverlay.classList.add('show');
        });
    }

    if (prevBtn && modalOverlay) {
        prevBtn.addEventListener('click', () => {
            if (isEditAndRepostMode) {
                modalOverlay.classList.remove('show');
                return;
            }

            if (currentStep === 1) {
                // Cancel
                modalOverlay.classList.remove('show');
            } else if (currentStep === 2) {
                // Go back to step 1
                step2.style.display = 'none';
                step1.style.display = 'block';
                stepper2.classList.remove('active');
                prevBtn.textContent = 'الغاء';
                currentStep = 1;
            } else if (currentStep === 3) {
                // Go back to step 2
                step3.style.display = 'none';
                step2.style.display = 'block';
                stepper3.classList.remove('active');
                nextBtn.textContent = 'التالي';
                prevBtn.className = 'modal-btn cancel-btn';
                currentStep = 2;
            }
        });
    }

    function submitRequest() {
        const type = selectedOption.querySelector('span').textContent;
        const time = document.getElementById('req-time').value || 'في اقرب وقت';
        const address = document.getElementById('req-address').value || 'العنوان غير محدد';
        const iconClass = selectedOption.querySelector('i').className;
    
        const list = document.querySelector('.requests-list');
        
        // Remove the empty state card if it exists
        const emptyState = list.querySelector('.status-card');
        if (emptyState) {
            emptyState.remove();
        }
    
        const cardHTML = `
            <div class="request-card" style="background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: 15px; align-items: center;">
                    <div style="background: #f0f7f0; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4b6a4a; font-size: 20px;">
                        <i class="${iconClass}"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--sidebar-bg);">${type}</h4>
                        <p style="font-size: 13px; color: var(--text-muted);">${address} • ${time}</p>
                    </div>
                </div>
                <div>
                    <span style="background: #e6f4ea; color: #1e8e3e; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">قيد الانتظار</span>
                </div>
            </div>
        `;
    
        list.insertAdjacentHTML('afterbegin', cardHTML);
    
        // Close modal
        modalOverlay.classList.remove('show');
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (isEditAndRepostMode) {
                modalOverlay.classList.remove('show');
                return;
            }

            if (currentStep === 1) {
                if (!selectedOption) {
                    alert('الرجاء اختيار نوع المساعدة أولاً');
                    return;
                }
                // Go to step 2
                step1.style.display = 'none';
                step2.style.display = 'block';
                stepper2.classList.add('active');
                prevBtn.textContent = 'السابق';
                currentStep = 2;
            } else if (currentStep === 2) {
                // Populate step 3 data
                const type = selectedOption.querySelector('span').textContent;
                const address = document.getElementById('req-address').value || 'حي الرمال _ شارع الوحدة'; // default for demo
                const time = document.getElementById('req-time').value || 'في اقرب وقت'; // default for demo
                
                document.getElementById('summary-type').textContent = type;
                document.getElementById('summary-address').textContent = address;
                document.getElementById('summary-time').textContent = time;

                // Go to step 3
                step2.style.display = 'none';
                step3.style.display = 'block';
                stepper3.classList.add('active');
                
                nextBtn.textContent = 'ارسال طلب';
                prevBtn.className = 'modal-btn next-btn'; // Make previous button dark green in step 3
                currentStep = 3;
            } else if (currentStep === 3) {
                submitRequest();
            }
        });
    }

    // Close modal when clicking outside
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove('show');
            }
        });
    }

    // Option selection logic
    modalOptions.forEach(option => {
        option.addEventListener('click', () => {
            modalOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            selectedOption = option;
        });
    });

    // Auto-open modal and jump to step 2 if 'service' URL param is present
    const urlParams = new URLSearchParams(window.location.search);
    const serviceParam = urlParams.get('service');
    
    if (serviceParam && modalOverlay) {
        resetModal();
        modalOverlay.classList.add('show');
        
        let matchedOption = Array.from(modalOptions).find(opt => opt.querySelector('span').textContent.trim() === serviceParam);
        
        if (!matchedOption) {
            matchedOption = Array.from(modalOptions).find(opt => opt.querySelector('span').textContent.trim() === 'اخرى');
            if (matchedOption) {
                // Update text to match what user typed
                matchedOption.querySelector('span').textContent = serviceParam; 
            }
        }
        
        if (matchedOption) {
            matchedOption.classList.add('selected');
            selectedOption = matchedOption;
            
            step1.style.display = 'none';
            step2.style.display = 'block';
            stepper2.classList.add('active');
            prevBtn.textContent = 'السابق';
            currentStep = 2;
        }
        
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // Handle clicks on service cards in request-help.html
    const requestHelpGrid = document.querySelector('.services-grid');
    if (requestHelpGrid) {
        const cards = requestHelpGrid.querySelectorAll('.service-card');
        cards.forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', (e) => {
                if (e.target.tagName.toLowerCase() === 'input') return;
                
                let serviceType = '';
                if (card.classList.contains('other-card')) {
                    const input = card.querySelector('.other-input');
                    serviceType = (input && input.value.trim() !== '') ? input.value.trim() : 'اخرى';
                } else {
                    const h3 = card.querySelector('h3');
                    if (h3) serviceType = h3.textContent.trim();
                }
                
                if (serviceType) {
                    window.location.href = `my-requests.html?service=${encodeURIComponent(serviceType)}`;
                }
            });
        });

        const otherInput = document.querySelector('.other-input');
        if (otherInput) {
            otherInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && otherInput.value.trim() !== '') {
                    window.location.href = `my-requests.html?service=${encodeURIComponent(otherInput.value.trim())}`;
                }
            });
        }
    }
});
