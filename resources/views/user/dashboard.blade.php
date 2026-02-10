@extends('layouts.dashboard-layout')

@section('styles')
<style>
    .main-content {
        padding: var(--spacing-2xl) var(--spacing-xl) !important;
        background: var(--color-bg-secondary);
    }
    
    .dashboard-header {
        margin-bottom: var(--spacing-2xl);
    }
    
    .dashboard-header h1 {
        font-size: var(--font-size-3xl);
        font-weight: var(--font-weight-bold);
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-sm);
    }
    
    .stat-card {
        background: var(--color-bg-primary);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        box-shadow: var(--shadow-sm);
        padding: var(--spacing-lg);
        transition: all var(--transition-base);
        height: 100%;
    }
    
    .stat-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
        border-color: var(--color-primary);
    }
    
    .stat-card.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        border-color: var(--color-primary);
        color: white;
    }
    
    .stat-card.active .stat-value,
    .stat-card.active .stat-label {
        color: white;
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: var(--spacing-md);
        background: var(--color-primary-50);
        color: var(--color-primary);
    }
    
    .stat-card.active .stat-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .stat-value {
        font-size: var(--font-size-3xl);
        font-weight: var(--font-weight-bold);
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-xs);
    }
    
    .stat-label {
        font-size: var(--font-size-base);
        font-weight: var(--font-weight-medium);
        color: var(--color-text-secondary);
    }
</style>
@endsection

@section('dashboard-content') 
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p style="color: var(--color-text-secondary);">Overview of your newsfeed activity</p>
    </div>
    
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="stat-value">{{ $stats['total_posts'] ?? 0 }}</div>
                <div class="stat-label">Total Posts</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-value">{{ $stats['total_comments'] ?? 0 }}</div>
                <div class="stat-label">Total Comments</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-value">{{ $stats['total_reactions'] ?? 0 }}</div>
                <div class="stat-label">Total Reactions</div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-share"></i>
                </div>
                <div class="stat-value">{{ $stats['total_shares'] ?? 0 }}</div>
                <div class="stat-label">Total Shares</div>
            </div>
        </div>
    </div>
@endsection
