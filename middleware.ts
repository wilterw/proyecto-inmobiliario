import { withAuth } from "next-auth/middleware"

export default withAuth(
  function middleware(req) {
    // Middleware logic here if needed
  },
  {
    callbacks: {
      authorized: ({ token, req }) => {
        // Protect admin routes
        if (req.nextUrl.pathname.startsWith("/admin")) {
          return token?.role === "ADMIN"
        }
        return true
      },
    },
  },
)

export const config = {
  matcher: ["/admin/:path*"],
}
