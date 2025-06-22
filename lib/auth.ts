import type { NextAuthOptions } from "next-auth"
import { PrismaAdapter } from "@next-auth/prisma-adapter"
import CredentialsProvider from "next-auth/providers/credentials"
import { prisma } from "./prisma"

export const authOptions: NextAuthOptions = {
  adapter: PrismaAdapter(prisma),
  providers: [
    CredentialsProvider({
      name: "credentials",
      credentials: {
        email: { label: "Email", type: "email" },
        password: { label: "Password", type: "password" },
      },
      async authorize(credentials) {
        if (!credentials?.email) return null

        // Para demo, cualquier email con @admin.com es admin
        if (credentials.email.includes("@admin.com")) {
          const user = await prisma.user.upsert({
            where: { email: credentials.email },
            update: {},
            create: {
              email: credentials.email,
              name: "Administrador",
              role: "ADMIN",
            },
          })
          return user
        }

        return null
      },
    }),
  ],
  callbacks: {
    session: ({ session, token }) => ({
      ...session,
      user: {
        ...session.user,
        id: token.sub,
        role: token.role,
      },
    }),
    jwt: ({ token, user }) => {
      if (user) {
        token.role = user.role
      }
      return token
    },
  },
  pages: {
    signIn: "/admin/login",
  },
  session: {
    strategy: "jwt",
  },
}
