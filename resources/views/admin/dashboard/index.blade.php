@extends('admin.layouts.app')

@section('title', 'Tổng Quan Quản Trị')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>Tổng Quan <span class="gradient-text">Hệ Thống TechHub</span></h1>
    <p style="margin-top: 0.25rem;">Thống kê tổng thể người dùng, công cụ thực thi, lượt chạy và quảng cáo.</p>
</div>

{{-- Top 4 KPI Metrics --}}
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 2.5rem;">
    <div class="stat-box">
        <span class="stat-number gradient-text">{{ number_format($totalUsers) }}</span>
        <span class="stat-label">Tổng Thành Viên</span>
    </div>
    <div class="stat-box">
        <span class="stat-number" style="color: var(--accent-cyan);">{{ $activeTools }} / {{ $totalTools }}</span>
        <span class="stat-label">Công Cụ Hoạt Động</span>
    </div>
    <div class="stat-box">
        <span class="stat-number" style="color: var(--accent-emerald);">{{ number_format($totalExecutions) }}</span>
        <span class="stat-label">Lượt Thực Thi (Runs)</span>
    </div>
    <div class="stat-box">
        <span class="stat-number" style="color: var(--accent-violet);">{{ $activeAds }} Banner ({{ $totalAdClicks }} clicks)</span>
        <span class="stat-label">Quảng Cáo Đang Chạy</span>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
    
    {{-- Recent Executions Log --}}
    <div class="admin-table-wrap">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.1rem; color: var(--text-main);">Lịch Sử Thực Thi Gần Nhất</h3>
            <span class="badge badge-emerald">Real-time Log</span>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Công Cụ</th>
                    <th>Trạng Thái</th>
                    <th>Thời Gian (ms)</th>
                    <th>IP / Thời Gian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentExecutions as $exec)
                    <tr>
                        <td>
                            <strong style="color: var(--text-main);">{{ $exec->tool?->name ?? 'Công cụ #' . $exec->tool_id }}</strong>
                        </td>
                        <td>
                            <span class="badge {{ $exec->status->value === 'completed' ? 'badge-emerald' : 'badge-danger' }}">
                                {{ $exec->status->value }}
                            </span>
                        </td>
                        <td style="font-family: var(--font-mono); color: var(--accent-cyan);">
                            {{ $exec->execution_time_ms }} ms
                        </td>
                        <td style="font-size: 0.82rem; color: var(--text-muted);">
                            {{ $exec->ip_address }} • {{ $exec->created_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Chưa có dữ liệu thực thi nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Top Used Tools --}}
    <div class="tool-card">
        <h3 style="font-size: 1.1rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem; color: var(--text-main);">
            <x-heroicon-o-fire style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Top Công Cụ Hot Nhất
        </h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($topTools as $tool)
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.92rem;">
                    <div>
                        <strong style="color: var(--text-main); display: block;">{{ $tool->name }}</strong>
                        <span style="font-size: 0.78rem; color: var(--text-muted);">{{ $tool->category?->name }}</span>
                    </div>
                    <span class="badge" style="font-family: var(--font-mono); color: var(--accent-emerald);">
                        {{ number_format($tool->execution_count) }} runs
                    </span>
                </div>
            @endforeach
        </div>
    </div>

</div>

@endsection
