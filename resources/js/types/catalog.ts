export interface CatalogCourse {
    slug: string;
    title: string;
    summary: string;
    thumbnailUrl: string | null;
    priceIdr: number;
    level: string;
    durationMinutes: number;
    category: string;
    categorySlug: string;
    instructor: string;
}

export interface CatalogFilters {
    q: string;
    category: string;
    level: string;
    price: string;
    sort: string;
}

export interface FilterOption {
    value: string;
    label: string;
}

export interface PaginatedCourses {
    data: CatalogCourse[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

export interface CourseLesson {
    id: number;
    title: string;
    type: 'video' | 'article' | 'document';
    durationSeconds: number;
    isPreview: boolean;
}

export interface CourseSection {
    id: number;
    title: string;
    description: string | null;
    lessons: CourseLesson[];
}

export interface CourseDetail extends CatalogCourse {
    description: string;
    outcomes: string[];
    requirements: string[];
    audience: string[];
    language: string;
    publishedAt: string | null;
    instructorProfile: {
        name: string;
        headline: string | null;
        bio: string | null;
    };
    sections: CourseSection[];
}
