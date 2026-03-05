@extends('layouts.dashboard')

@section('title', 'إعدادات الحساب')

@section('content')
    <div class="welcome-section" style="margin-bottom: 32px;">
        <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">إعدادات النظام ⚙️
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">إدارة ملفك الشخصي، إعدادات الأمان، وتفضيلات النظام.</p>
    </div>

    <div class="glass-card" style="padding: 40px;" dir="rtl">
        <!-- Tabs Navigation -->
        <nav class="settings-tabs">
            <div class="tab-item active" onclick="switchTab('profile')">الملف الشخصي</div>
            <div class="tab-item" onclick="switchTab('security')">الأمان والخصوصية</div>
            <div class="tab-item" onclick="switchTab('notifications')">التنبيهات</div>
            <div class="tab-item" onclick="switchTab('appearance')">المظهر والنظام</div>
        </nav>

        <!-- Profile Tab -->
        <div id="profile" class="tab-content active">
            <div class="settings-grid">
                <div class="settings-sidebar">
                    <h3>معلومات الحساب</h3>
                    <p>قم بتحديث صورتك الشخصية وتفاصيل حسابك المهنية.</p>
                </div>
                <div class="settings-main">
                    <form action="{{ route('doctor.settings.update') }}" method="POST">
                        @csrf
                        <div class="form-section">
                            <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 32px;">
                                <img src="{{ $user['avatar'] }}" alt="Avatar"
                                    style="width: 80px; height: 80px; border-radius: 20px;">
                                <div>
                                    <button type="button" class="btn-primary-sm"
                                        style="width: auto; padding: 8px 16px;">تغيير الصورة</button>
                                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 8px;">JPG, GIF أو
                                        PNG. بحد أقصى 800 كيلوبايت.</p>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div class="form-group">
                                    <label class="input-label">الاسم الكامل</label>
                                    <input type="text" class="form-control" style="padding-left: 16px;"
                                        value="{{ $user['name'] }}">
                                </div>
                                <div class="form-group">
                                    <label class="input-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" style="padding-left: 16px;"
                                        value="{{ $user['email'] }}">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label class="input-label">التخصص الطبي</label>
                                <input type="text" class="form-control" style="padding-left: 16px;"
                                    value="{{ $user['specialization'] }}">
                            </div>

                            <div class="form-group">
                                <label class="input-label">نبذة تعريفية</label>
                                <textarea class="form-control"
                                    style="padding-left: 16px; height: 100px; resize: none;">{{ $user['bio'] }}</textarea>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn-primary" style="width: auto; padding: 12px 32px;">حفظ
                                التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Tab (Hidden by default) -->
        <div id="security" class="tab-content" style="display: none;">
            <div class="settings-grid">
                <div class="settings-sidebar">
                    <h3>الأمان</h3>
                    <p>قم بإدارة كلمة المرور وإعدادات التحقق بخطوتين.</p>
                </div>
                <div class="settings-main">
                    <div class="settings-card" style="margin-bottom: 24px;">
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label class="input-label">كلمة المرور الحالية</label>
                            <input type="password" class="form-control" style="padding-left: 16px;" placeholder="••••••••">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="input-label">كلمة المرور الجديدة</label>
                                <input type="password" class="form-control" style="padding-left: 16px;">
                            </div>
                            <div class="form-group">
                                <label class="input-label">تأكيد كلمة المرور</label>
                                <input type="password" class="form-control" style="padding-left: 16px;">
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="switch-wrapper">
                            <div class="switch-info">
                                <h4>التحقق بخطوتين (2FA)</h4>
                                <p>إضافة طبقة حماية إضافية لحسابك.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" {{ $user['security']['two_factor'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Tab (Hidden by default) -->
        <div id="notifications" class="tab-content" style="display: none;">
            <div class="settings-grid">
                <div class="settings-sidebar">
                    <h3>التنبيهات</h3>
                    <p>اختر كيف ومتى تود استلام التنبيهات من النظام.</p>
                </div>
                <div class="settings-main">
                    <div class="settings-card">
                        <div class="switch-wrapper">
                            <div class="switch-info">
                                <h4>تنبيهات البريد الإلكتروني</h4>
                                <p>استلام ملخص يومي للمواعيد والتقارير.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" {{ $user['notifications']['email'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="switch-wrapper">
                            <div class="switch-info">
                                <h4>تنبيهات الرسائل النصية (SMS)</h4>
                                <p>تنبيهات فورية للحالات الطارئة والمواعيد العاجلة.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" {{ $user['notifications']['sms'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="switch-wrapper">
                            <div class="switch-info">
                                <h4>تحديثات التقارير</h4>
                                <p>عندما يصبح التقرير الطبي جاهزاً للمراجعة.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" {{ $user['notifications']['reports'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });

            // Remove active class from tabs
            document.querySelectorAll('.tab-item').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabId).style.display = 'block';

            // Add active class to clicked tab
            event.currentTarget.classList.add('active');
        }
    </script>
@endsection